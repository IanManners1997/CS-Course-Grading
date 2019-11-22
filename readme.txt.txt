Fall 2019 Database Functions for CS Course Grading App
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
/*-------------------------------------------------------------------------------
        TEACHER FUNCTIONS
    -------------------------------------------------------------------------------*/
getTeacherByUsername($username) 
returns teacher based on username

getTeacherByID($id) 
returns teacher uses sql id number

getTeacherBySection($section) 
returns teacher based on section number

removeTeacher($username) 
tries to remove a teacher from the database, cannot remove a teacher if he has classes

getTeacherByGraderUsername($id)
This will return all the teachers that the grader has sections in common with

createTeacher($teacher) 
this takes in ["FirstName LastName", "username"]
creates a teacher in the database and returns the teacher

updateTeacher($currID, $newInfo)
Takes in the teachers USERNAME
updates any info, info form ["Firstname lastname", "username"]

/*-------------------------------------------------------------------------------
        GRADER FUNCTIONS
    -------------------------------------------------------------------------------*/
--The following are the same or similar to the teacher functions --
getGraderBySection($section)
getGradersByTeacherUsername($teacherusername)
getGraderByID($id)
getGraderByUsername($username)
createGrader($grader)  same form as teacher in createTeacher
updateGrader($currID, $newInfo)
--New/Different Functions--
removeGraderS($username, $section)
Removes a grader from a section by username
Cleans up the grader table to remove him entirely if he has no more sections

removeGraderC($username)
removes a grader from all of his sections by username
removes the grader from grader table
/*-------------------------------------------------------------------------------
        STUDENT FUNCTIONS
    -------------------------------------------------------------------------------*/
All by username
createStudent($student)
creates a student with username = $student

getStudents($section)
returns all students of a given section

studentsToFile($path, $section)
writes all students in a section to the file in $path

addStudent($id, $section)
creates a student if needed
adds student to the section

removeStudentS($id, $section)
removes a student from a section
if the student has no more sections removes the student entirely

removeStudentC($id)
removes as student entirely

/*-------------------------------------------------------------------------------
        SECTION FUNCTIONS
    -------------------------------------------------------------------------------*/
fillSection($section, $sIDs)
Fills a section with an array of student usernames

updateTeacherSection($teacher, $section)
updates the teacher of a section, using a $teacher object returned from any getTeacher or createTeacher function
DOES NOT create section

updateGraderSection($grader, $section)
same as above

addSection($teacher, $grader, $section)
takes in teacher and grader objects
creates a section or updates it

getSections($teacherid, $graderid)
Function supports up to two args, but only requires teacherid to be successful
if -1 is passed in for teacherid, it will get all sections of the grader
if only teacherid is passed in, it will get all sections of that teacher
if both are passed in, it will get all sections where teacher=teacher and grader=grader

removeSection($section)
removes a section via section number from the database
Performs cleanup of students

addGrader($name, $id, $section)
Creates and adds a grader to a section
$name is full name aka FIRSTNAME "space" LASTNAME
$id is the username

addTeacher($name, $id, $section)
Same as above

createSection($teacher, $grader, $students, $section)
Creates the section, teacher, and grader if needed
Adds the section
Fills the section with the students
students is an array of student usernames

/*-------------------------------------------------------------------------------
        DATABASE FUNCTIONS
    -------------------------------------------------------------------------------*/
truncateAll()
Removes all data from tables

dropAll()
drops all tables

createTables()
recreates all tables

DUMP functions:
dumpTeachers()
dumpGraders()
dumpStudents()
dumpSections()
dump()
Dump respective tables
