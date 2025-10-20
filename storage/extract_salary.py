import json
import pdfplumber
path = "storage/P_3-1_112_20230101.pdf"
rows = []
with pdfplumber.open(path) as pdf:
    for page in pdf.pages:
        for table in page.extract_tables():
            for row in table:
                if row:
                    rows.append(row)
    
with open("storage/salary_table.json", "w", encoding="utf-8") as f:
    json.dump(rows, f, ensure_ascii=False, indent=2)
