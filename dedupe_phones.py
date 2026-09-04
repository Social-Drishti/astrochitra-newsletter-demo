import csv

input_path = r"C:\Users\vinay\OneDrive\Desktop\SD\astrochitra-newsletter-demo\phones_cleaned.csv"
output_path = r"C:\Users\vinay\OneDrive\Desktop\SD\astrochitra-newsletter-demo\phones_import.csv"

seen = set()
with open(input_path, newline='', encoding='utf-8') as infile, open(output_path, 'w', newline='', encoding='utf-8') as outfile:
    reader = csv.DictReader(infile)
    writer = csv.writer(outfile)
    writer.writerow(['name','phone'])  # import format
    for row in reader:
        phone = row['phone_corrected']
        if phone not in seen:
            seen.add(phone)
            writer.writerow([row['name'], phone])
print(f"Written {len(seen)} unique contacts to {output_path}")