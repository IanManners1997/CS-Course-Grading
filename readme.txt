Fall 2019 CS Course Grading Application Readme
Created by: Adam Ibrahim

Files Required:
db_connect.php
sqlFunctions.php

IMPORTANT:
db_connect.php provides access to the database
the coder MUST REQUIRE db_connect.php if they want to implement their own functions
this provides access to the variable $conn
$conn can be used to run sql queries

All other functions are provided in sqlFunctions.php
sqlFunctions.php requires db_connect.php

Functions to access hoffmant_grading database in sqlFunctions.php:

*note
When ID is stated it refers to the university of pittsburgh login name without the @pitt.edu

addSections( string filename )
Opens the file of filename and add the contents to the database as long as it is in the correct format.
The file form should be:
Section #{
TEACHER_NAME, TEACHER_ID
GRADER_NAME, GRADER_ID
STUDENT_ID, STUDENT_ID, ..., 
}
NextSection#{
TEACHER_NAME,ID
GRADER_NAME,ID
STUDENTID,ID,ID
}
*rules on how it actually breaks apart the file*
searches for {
finds the integer on that line and adds it as the section id
reads in the next 3 lines and separates all info
repeats

Example:
33302{
Tim Hoffman, hoffmant
Adam Ibrahim, adi11
stx32, tst93, lmk00, eri49
}
34302{
Test Teach2, tesstoo2
Test Grader2, tst112
shp43, mgt55, mly66, lui76, pox44, cmd43, ewl12
}
If the section already exists, the user will be displayed the current section info alongside what he is trying to add and asked if they want to replace it.


getSections(args)
This function is overloaded. First argument must always be the teacher ID, second argument is the grader ID
If more than two arguments are passed in, it will return an error.
If the only the Teacher ID is passed in, it will return all sections for that teacher.
If both the teacher and grader ID are passed in, it will return the section for the grader.
Always returns the section/s as an array

getStudents($section_number)
returns an array of student IDs in that section

studentsToFile(section_id)
Grabs all student IDs within a section and writes them to a txt file named "SECTION_ID.txt"

addStudent(student_id, section_id)
adds a student to a section
if the student already exists it will return false.
If the student is successfully added, return true.
the section MUST already exist.
