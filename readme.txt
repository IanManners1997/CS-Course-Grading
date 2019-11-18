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

separateFile( string filename )
Opens the file of filename and add the contents to the database.
Inserts into, if insert fails due to row existing, it updates the row.
This now also accepts JSON!
Javascript object format: If no grader, set grader to null
section = {
    number : 44300, //section number
    teacher :   { //teacher information
        firstname : "Tim",
        lastname : "hoffman",
        email : "hoffmant@pitt.edu"
    },
    grader : { //grader information
        firstname : "test",
        lastname : "testname",
        email : "test@pitt.edu"
    }, //array of students
    students : ["tst32", "plo45", "lit49", "tko95", "tms90", "elp43", "rmn433", "men459", "rmi54", "fnd33", "wmn495", "fem95", "plo99", "rew23", "sdf43"]
};
The other file format should be:
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
If both the teacher and grader ID are passed in, it will return the sections for the grader.
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

dump()
    dumps the contents of the database
other dumps:
    dumpTeachers() dumpGraders() dumpStudents() dumpSections()
truncateAll()
    Removes all contents from database

addGrader(name, id, section)
Adds a grader to a database and/or section.
If the grader exists it will add to the section.
If the section already has a grader, it will update the database with the new grader.
If the previous grader has no more sections it will delete that grader.

addTeacher(name, id, section)
Adds a teacher to a section
*NOTE*
A section is not allowed to have no teacher!
So, the only way to add a new section other than from a file
is to add a teacher to it, creating an empty section.

removeStudent(id, section)
Removes a student from a section, if the student has no other courses, removes the student from the database.

removeGrader(id, section)
removes a grader from the section, if the grader has no more sections
removes the grader.

removeTeacher(id)
Removes a teacher from the database
*NOTE* Can only remove a teacher if he has no sections!

removeSection($section)
Removes a section from the db, if the students inside have no other classes, it removes them from the db too
If the grader has no more section it removes them from the db too.
Will not attempt to remove the teacher.


