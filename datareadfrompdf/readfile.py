import tabula

file2 = "assignment.pdf"
# To read table in first page of PDF file
table1 = tabula.read_pdf(file2 ,pages=4)
# To read tables in secord page of PDF file
table2 = tabula.read_pdf(file2 ,pages=5)
table3 = tabula.read_pdf(file2 ,pages=6)
# print(table1[0])
# print(table2[0])
# print(table3[0])

tabula.convert_into(file2, "assignment_student.csv",pages='4,5,6')