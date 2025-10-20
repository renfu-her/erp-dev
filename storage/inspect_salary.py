import json
with open("storage/salary_table.json", encoding="utf-8") as f:
    rows = json.load(f)
for row in rows[-10:]:
    print([cell.encode("unicode_escape").decode("ascii") if cell else None for cell in row])
