import os
import glob
import re

form_dir = r'c:\Users\arjun\Documents\Magang\Project Magang\lentera\resources\views\pelayanan\landing\forms'
files = glob.glob(os.path.join(form_dir, '*.blade.php'))

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Update hidden input jenis_layanan to jenis_layanan_slug
    content = re.sub(r'<input type="hidden" name="jenis_layanan" value="[^"]*" />', 
                     r'<input type="hidden" name="jenis_layanan_slug" value="{{ $jenisLayanan->slug ?? \'\' }}" />', 
                     content)

    # Standardize names
    content = content.replace('name="tempat_kkl"', 'name="tempat_kegiatan"')
    content = content.replace('name="tempat_penelitian"', 'name="tempat_kegiatan"')
    content = content.replace('name="tempat_pelaksanaan"', 'name="tempat_kegiatan"')
    content = content.replace('name="judul_kkn"', 'name="judul_kegiatan"')
    content = content.replace('name="tema_kegiatan"', 'name="judul_kegiatan"')
    content = content.replace('name="asal_sekolah"', 'name="asal_instansi"')
    content = content.replace('name="asal_institusi"', 'name="asal_instansi"')
    content = content.replace('name="file_ktm"', 'name="file_ktm"') # just making sure it's intact
    content = content.replace('name="file_surat_lokasi"', 'name="file_surat_lokasi"')
    
    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)

print("Blade forms updated successfully.")
