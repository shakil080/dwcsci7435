import csv
from mysql.connector import connect

def getMajorID(majorlist, majorname):
    for row in majorlist:
        if row[1] == majorname:
            return row[0]


def getSemesterID(semesterlist, monthday):
    for row in semesterlist:
        if row[2] == monthday:
            return row[0]

def getTimeID(timelist, year, semesterid):
    for row in timelist:
        if row[1] == year and row[2] == semesterid:
            return row[0]

def getIndividualGPARange(GPA):
    if float(GPA) <= 2:
        return "low"
    
    if float(GPA) >2 and float(GPA) <3:
        return "medium"
    
    if float(GPA) >=3:
        return "high"

db = connect(host = "localhost", user = "root", passwd = "root143#", db = "gradstudentdw")
cursor = db.cursor()
f = open('assignment_student.csv', 'r')
csv_data = csv.reader(f)
next(csv_data)
# query = """INSERT INTO address (address_info) VALUES (%s)"""
query_time_insert = """INSERT INTO Time (timeid, year, semesterid) VALUES (%s, %s, %s)"""

time_id = 1
# inserting six semester for each year from 80 to 99
for y in range(80, 100):
    # print(y)
    for s in range(1,7):
        # print(y, s)
        cursor.execute(query_time_insert, (time_id, y, s))
        db.commit()
        time_id = time_id + 1

query_major = "SELECT * FROM major"
cursor.execute(query_major)
major_rows = cursor.fetchall()
# print(major_rows)
print(getMajorID(major_rows, 'Information Sc'))


query_semester = "SELECT * FROM semester"
cursor.execute(query_semester)
semester_rows = cursor.fetchall()
# print(semester_rows)
print(getSemesterID(semester_rows, 'Dec. 15'))

query_time = "SELECT * FROM time"
cursor.execute(query_time)
time_rows = cursor.fetchall()
# print(time_rows)
print(getTimeID(time_rows, '89', 1))

# inserting in Student table
query_student_insert = """INSERT INTO Student (studentid,name,address,gpa,majorid,statusid,degreeid) VALUES (%s, %s, %s, %s, %s, %s, %s)"""

# inserting in GradStudentInformation Fact table
query_gradstudentinfo_insert = """INSERT INTO GradStudentInforamtion (studentid, timeid, gparange) VALUES (%s, %s, %s)"""

for row in csv_data:
    majorID = getMajorID(major_rows, row[3])
    cursor.execute(query_student_insert, (row[0], row[1], row[2], row[5], majorID, row[8], row[4]))
    db.commit()


for row in csv_data:
    semesterId = getSemesterID(semester_rows, row[6])
    timeID = getTimeID(time_rows, row[7], semesterId)
    individualGPA = getIndividualGPARange(row[5])

    print(query_gradstudentinfo_insert, (row[0], timeID, individualGPA))
    cursor.execute(query_gradstudentinfo_insert, (row[0], timeID, individualGPA))
    db.commit()




