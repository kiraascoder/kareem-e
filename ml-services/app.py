# ml-services/app.py
from fastapi import FastAPI
from pydantic import BaseModel
from datetime import datetime
from typing import Optional
import joblib
import os

from fuzzy_engine import compute_factor

app = FastAPI(
    title="Kareem ML Pricing Service",
    version="1.0.0",
    description="ML + fuzzy service untuk rekomendasi harga event."
)

MODEL_PATH = os.path.join(os.path.dirname(__file__), "rf_model.pkl")
rf_model = None
if os.path.exists(MODEL_PATH):
    try:
        rf_model = joblib.load(MODEL_PATH)
        print("[ML] RandomForest model loaded from rf_model.pkl")
    except Exception as e:
        print("[ML] Gagal load model:", e)
else:
    print("[ML] rf_model.pkl tidak ditemukan, pakai heuristik sederhana.")


class PricingRequest(BaseModel):
    jenis_event: str
    tanggal_event: str          # "YYYY-MM-DD"
    tanggal_booking: str        # "YYYY-MM-DD"
    jumlah_peserta: int
    harga_dasar: float
    season: Optional[str] = None    # "high" / "low" / None


class PricingResponse(BaseModel):
    lead_time: int
    permintaan_prediksi: float
    faktor_harga: float
    harga_rekomendasi: float
    season: Optional[str] = None
    model_version: Optional[str] = None


@app.get("/")
def root():
    return {"message": "ML Service running"}


@app.post("/predict-price", response_model=PricingResponse)
def predict_price(req: PricingRequest):
    # 1. hitung lead_time
    tanggal_event = datetime.strptime(req.tanggal_event, "%Y-%m-%d").date()
    tanggal_booking = datetime.strptime(req.tanggal_booking, "%Y-%m-%d").date()
    lead_time = abs((tanggal_event - tanggal_booking).days)

    season = req.season or "unknown"

    # 2. prediksi permintaan (pakai model kalau ada, kalau tidak heuristik)
    if rf_model is not None:
        # >>> SESUAIKAN DENGAN CARA KAMU MELATIH MODEL <<<
        # Contoh sangat sederhana: fitur = [jumlah_peserta, lead_time, season_high_flag]
        season_high_flag = 1 if season == "high" else 0
        X = [[req.jumlah_peserta, lead_time, season_high_flag]]
        try:
            demand_pred = float(rf_model.predict(X)[0])
        except Exception as e:
            print("[ML] Error saat prediksi model:", e)
            demand_pred = heuristic_demand(req.jumlah_peserta, lead_time, season)
        model_ver = "rf_model.pkl"
    else:
        demand_pred = heuristic_demand(req.jumlah_peserta, lead_time, season)
        model_ver = "heuristic-v1"

    # batasi ke [0,1]
    demand_pred = max(0.0, min(1.0, demand_pred))

    # 3. hitung faktor fuzzy (sebenarnya rule-based tapi kita treat sebagai fuzzy engine)
    faktor = compute_factor(
        demand=demand_pred,
        lead_time=lead_time,
        season=season
    )

    # 4. hitung harga rekomendasi
    harga_rekomendasi = req.harga_dasar * faktor

    return PricingResponse(
        lead_time=lead_time,
        permintaan_prediksi=demand_pred,
        faktor_harga=faktor,
        harga_rekomendasi=harga_rekomendasi,
        season=season,
        model_version=model_ver,
    )


def heuristic_demand(jumlah_peserta: int, lead_time: int, season: str) -> float:
    """
    Heuristik sederhana kalau model belum ada:
    - event besar -> demand sedikit lebih tinggi
    - high season -> tambah sedikit
    - lead time pendek -> tambah sedikit
    """
    base = 0.5

    if jumlah_peserta >= 200:
        base += 0.15
    elif jumlah_peserta >= 100:
        base += 0.10
    elif jumlah_peserta <= 50:
        base -= 0.05

    if season == "high":
        base += 0.1
    elif season == "low":
        base -= 0.05

    if lead_time <= 7:
        base += 0.1
    elif lead_time >= 60:
        base -= 0.05

    return base
