use gradstudentdw;
-- As we are testing and droping tables numerously, this will override foreign key check at the moment
SET FOREIGN_KEY_CHECKS=0; 
drop table if exists College;
drop table if exists Major;
drop table if exists Status;
drop table if exists Degree;
drop table if exists Semester;
drop table if exists Student;
drop table if exists Time;
drop table if exists GradStudentInformation;
SET FOREIGN_KEY_CHECKS=1; 

create table College (
    collegeid int, 
    collegename varchar(50), 
    constraint pkCollege primary key (collegeid)
);
-- Insert statements for College Table
INSERT INTO `College`(`collegeid`,`collegename`) VALUES (1, 'Cyber College');
INSERT INTO `College`(`collegeid`,`collegename`) VALUES (2, 'College of Business');
INSERT INTO `College`(`collegeid`,`collegename`) VALUES (3, 'College of Education');
INSERT INTO `College`(`collegeid`,`collegename`) VALUES (4, 'College of Art and Science');

create table Major(
    majorid int,
    majorname varchar(50),
    collegeid int,
    constraint pkMajor primary key (majorid),
    constraint fkCollegeId foreign key (collegeid) references College(collegeid)
);
-- Insert Statements for Major Table
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (1, 'Computer Sc', 1);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (2, 'Information Sc', 1);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (3, 'Applied Sc', 1);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (4, 'Accounting', 2);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (5, 'Business Admin', 2);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (6, 'Economics', 2);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (7, 'Elementary Ed', 3);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (8, 'Secondary Ed', 3);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (9, 'Biology', 4);
INSERT INTO `Major`(`majorid`,`majorname`,`collegeid`) VALUES (10, 'Chemistry', 4);

create table Status(
    statusid char(1),
    statusname varchar(50),
    constraint pkStatus primary key (statusid)
);

-- Insert Statements for Status Table
INSERT INTO `Status`(`statusid`,`statusname`) VALUES ('I', 'International Student');
INSERT INTO `Status`(`statusid`,`statusname`) VALUES ('O', 'Out of state');
INSERT INTO `Status`(`statusid`,`statusname`) VALUES ('N', 'Non-of-the above');

create table Degree(
    degreeid char(5),
    degreename varchar(50),
    constraint pkDegree primary key (degreeid)
);

-- Insert Statement for Degree table
INSERT INTO `Degree`(`degreeid`,`degreename`) VALUES ('AS', 'Associate degree');
INSERT INTO `Degree`(`degreeid`,`degreename`) VALUES ('BS', 'Bachelor of Science');
INSERT INTO `Degree`(`degreeid`,`degreename`) VALUES ('BA', 'Bachelor of Art');
INSERT INTO `Degree`(`degreeid`,`degreename`) VALUES ('MS', 'Master of Science');
INSERT INTO `Degree`(`degreeid`,`degreename`) VALUES ('MA', 'Master of Art');
INSERT INTO `Degree`(`degreeid`,`degreename`) VALUES ('MBA', 'Master of Business Administration');
INSERT INTO `Degree`(`degreeid`,`degreename`) VALUES ('EdD', 'Doctor of Education');
INSERT INTO `Degree`(`degreeid`,`degreename`) VALUES ('PhD', 'Doctor of Philosophy');

create table Semester(
    semesterid int,
    semestername varchar(50),
    monthday varchar(50),
    constraint pkSemester primary key (semesterid)
);

-- Insert Statement for Semester table
INSERT INTO `Semester`(`semesterid`, `semestername`, `monthday`) VALUES (1, 'Fall semester', 'Dec. 15');
INSERT INTO `Semester`(`semesterid`, `semestername`, `monthday`) VALUES (2, 'Spring semester', 'May. 15');
INSERT INTO `Semester`(`semesterid`, `semestername`, `monthday`) VALUES (3, 'Summer 1', 'June 1');
INSERT INTO `Semester`(`semesterid`, `semestername`, `monthday`) VALUES (4, 'Summer 2', 'July 4');
INSERT INTO `Semester`(`semesterid`, `semestername`, `monthday`) VALUES (5, 'Summer 3', 'July 15');
INSERT INTO `Semester`(`semesterid`, `semestername`, `monthday`) VALUES (6, 'Summer 4', 'Aug. 15');

create table Time(
    timeid int,
    year varchar(4),
    semesterid int,
    constraint pkTime primary key (timeid),
    constraint fkSemesterId foreign key (semesterid) references Semester(semesterid)
);

create table Student(
    studentid int,
    name varchar(50),
    address varchar(50),
    gpa float,
    majorid int,
    statusid char(1),
    degreeid char(5),
    constraint pkStudent primary key (studentid),
    constraint fkMajorId foreign key (majorid) references Major(majorid),
    constraint fkStatusID foreign key (statusid) references Status(statusid),
    constraint fkDegreeId foreign key (degreeid) references Degree(degreeid)
);

create table GradStudentInformation (
  studentid int not null, 
  timeid int not null,
  gparange char(10),
  constraint pkGradStudentInformation primary key (studentid, timeid),
  constraint fkStudent foreign key (studentid) references Student(studentid),
  constraint fkTime foreign key (timeid) references Time(timeid)
);