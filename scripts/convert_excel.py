import pandas as pd
import json

df = pd.read_excel(r'c:\Users\arjun\Documents\Magang\Project Magang\lentera\rencana\NAMA_DINAS_KEC_UK.xlsx')
data = []
for index, row in df.iterrows():
    instansi = str(row['INSTANSI']).strip()
    unit_kerja = str(row['UNIT KERJA']).strip()
    if instansi != 'nan' and unit_kerja != 'nan':
        data.append({
            'instansi': instansi,
            'unit_kerja': unit_kerja
        })

with open(r'c:\Users\arjun\Documents\Magang\Project Magang\lentera\database\seeders\dinas_bidang.json', 'w') as f:
    json.dump(data, f, indent=4)
print("JSON Exported Successfully!")
