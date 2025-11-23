from fastapi import FastAPI
from pydantic import BaseModel

app = FastAPI()


class PricingRequest(BaseModel):
    jenis_event: str
    tanggal_event: str
    tanggal_booking: str
    jumlah_peserta: int
    harga_dasar: float
    season: str | None = None


@app.get("/")
def read_root():
    return {"message": "ML Service running"}


@app.post("/predict-price")
def predict_price(data: PricingRequest):    
    lead_time_dummy = 10
    permintaan_prediksi = 0.8
    faktor_harga = 1.2
    harga_rekomendasi = data.harga_dasar * faktor_harga

    return {
        "lead_time": lead_time_dummy,
        "permintaan_prediksi": permintaan_prediksi,
        "faktor_harga": faktor_harga,
        "harga_rekomendasi": harga_rekomendasi,
    }
