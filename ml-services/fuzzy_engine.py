def compute_factor(demand: float, lead_time: int, season: str) -> float:
    """
    Engine 'fuzzy' sederhana berbasis rule:
    - demand: 0.0–1.0
    - lead_time: hari
    - season: "high" / "low" / lainnya

    menghasilkan faktor_harga:
    - < 1.0  : diskon
    - = 1.0  : normal
    - > 1.0  : kenaikan
    """

 
    if demand < 0.4:
        demand_cat = "low"
    elif demand < 0.7:
        demand_cat = "medium"
    else:
        demand_cat = "high"


    if lead_time <= 7:
        lt_cat = "short"
    elif lead_time <= 30:
        lt_cat = "medium"
    else:
        lt_cat = "long"

    season = (season or "unknown").lower()


    factor = 1.0

   
    if season == "high" and demand_cat == "high" and lt_cat == "short":
        factor = 1.30
  
    elif season == "high" and demand_cat == "high":
        factor = 1.20
 
    elif season == "high" and demand_cat == "medium":
        factor = 1.10
 
    elif season == "high" and demand_cat == "low":
        factor = 1.05


    elif season == "low" and demand_cat == "high":
        factor = 1.10
    elif season == "low" and demand_cat == "medium":
    
        factor = 1.0 if lt_cat == "short" else 0.95
    elif season == "low" and demand_cat == "low":
        if lt_cat == "long":
            factor = 0.85
        else:
            factor = 0.90

    else:
        if demand_cat == "high":
            factor = 1.10
        elif demand_cat == "medium":
            factor = 1.0
        else:
            factor = 0.95

    return factor
