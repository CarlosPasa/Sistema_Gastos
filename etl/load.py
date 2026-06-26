import os
from datetime import datetime


def export_dataframe(df, filename):
    os.makedirs("output", exist_ok=True)

    csv_path = f"output/{filename}.csv"
    excel_path = f"output/{filename}.xlsx"

    df.to_csv(csv_path, index=False, encoding="utf-8-sig")
    df.to_excel(excel_path, index=False)

    print(f"{filename}.csv generado")
    print(f"{filename}.xlsx generado")
