from fastapi import FastAPI
from pydantic import BaseModel

app = FastAPI()

class EventData(BaseModel):
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
def predict_price(data: EventData):    
    return {
        "permintaan_prediksi": 0.8,
        "faktor_harga": 1.2,
        "harga_rekomendasi": data.harga_dasar * 1.2
    }
