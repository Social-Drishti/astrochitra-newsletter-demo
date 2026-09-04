import csv

input_path = r"C:\Users\vinay\OneDrive\Desktop\SD\astrochitra-newsletter-demo\phones_import.csv"
output_path = r"C:\Users\vinay\OneDrive\Desktop\SD\astrochitra-newsletter-demo\phones_import_template.csv"

with open(input_path, newline='', encoding='utf-8') as infile, open(output_path, 'w', newline='', encoding='utf-8') as outfile:
    reader = csv.DictReader(infile)
    fieldnames = ['name','email','phone','rashi','source']
    writer = csv.DictWriter(outfile, fieldnames=fieldnames)
    writer.writeheader()
    for row in reader:
        writer.writerow({
            'name': row['name'],
            'email': '',
            'phone': row['phone'],
            'rashi': '',
            'source': ''
        })
print("Template CSV created")