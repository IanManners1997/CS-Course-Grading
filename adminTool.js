TeacherElement = document.getElementById("Teacher");
GraderElement = document.getElementById("Grader");
StudentElement = document.getElementById("Student");
SectionElement = document.getElementById("Section");
miscElement = document.getElementById("Misc");

TeacherElement.addEventListener("click", clickedTeacherElement);

GraderElement.addEventListener("click", clickedGraderElement);

StudentElement.addEventListener("click", clickedStudentElement);

SectionElement.addEventListener("click", clickedSectionElement);

miscElement.addEventListener("click", clickedMiscElement);

function clickedTeacherElement(){
    clearMainDiv();
    createTable("Teacher");
}
function clickedGraderElement(){
    clearMainDiv();
    createTable("Grader");
}
function clickedStudentElement(){
    clearMainDiv();
    createTable("Student");
}
function clickedSectionElement(){
    clearMainDiv();
    createTable("Section");
}
function clickedMiscElement(){
    clearMainDiv();
    createTable("Misc");
}
function createTable(clickedElement){
    //create row element
    row = document.createElement("displayRow");
    row.id = "displayDivRow";
    row.classList.add("row");
    document.getElementById("displayDiv").appendChild(row);
    /*
        Common elements are 
        add
        update
        remove
        dump
    */

    //Creating the columns for each button
    add = document.createElement("Add");
    add.classList.add("column");
    add.id = "addColumn";
    add.innerHTML = "Add " + clickedElement + "<br>";
    update = document.createElement("Update");
    update.innerHTML = "Update " + clickedElement + "<br>";
    update.id = "updateColumn";
    update.classList.add("column");
    remove = document.createElement("Remove");
    remove.innerHTML = "Remove " + clickedElement + "<br>";
    remove.id = "removeColumn";
    remove.classList.add("column");
    dump = document.createElement("Dump");
    dump.innerHTML = "Dump " + clickedElement + "<br>";
    dump.id = "dumpColumn";
    dump.classList.add("column");

    //appending to the rows
    row.appendChild(add);
    row.appendChild(update);
    row.appendChild(remove);
    row.appendChild(dump);

    if(clickedElement == "Misc")
        return createMiscMenu();
    //create all input fields
    //pass in clicked element to tell it which buttons to create
    createFields(clickedElement);

    //create all submit buttons
    //pass in the clicked element to append it to the id of each button
    //so we know which function to call
    createButtons(clickedElement);
}
function createFields(clickedElement){
    if(clickedElement == "Teacher"){
        createTeacherFields();
    }else if(clickedElement == "Grader"){
        createGraderFields();
    }else if(clickedElement == "Student"){
        createStudentFields();
    }else if(clickedElement == "Section"){
        createSectionFields();
    }
}
function createTeacherFields(){
    //add fields
    var afname = document.createElement("aFirstName");
    afname.innerHTML = "<input type=\"text\" name=\"aTFName\" placeholder=\"Enter first name...\"><br>";

    var alname = document.createElement("aLastName");
    alname.innerHTML = "<input type=\"text\" name=\"aTLName\" placeholder=\"Enter last name...\"><br>";

    var auser = document.createElement("aUser");
    auser.innerHTML = "<input type=\"text\" name=\"aTUser\" placeholder=\"Enter username...\"><br>";
    
    //append fields
    appendElementToDisplayCol(afname, 0);
    appendElementToDisplayCol(alname, 0);
    appendElementToDisplayCol(auser, 0);

    //update fields
    //old info
    var uuser = document.createElement("uUser");
    uuser.innerHTML = "<input type=\"text\" name=\"uTUser\" placeholder=\"Enter current username...\"><br>";
    //new info
    var nufname = document.createElement("nuFirstName");
    nufname.innerHTML = "<input type=\"text\" name=\"nuTFName\" placeholder=\"Enter new first name...\"><br>";

    var nulname = document.createElement("nuLastName");
    nulname.innerHTML = "<input type=\"text\" name=\"nuTLName\" placeholder=\"Enter new last name...\"><br>";

    var nuuser = document.createElement("nuUser");
    nuuser.innerHTML = "<input type=\"text\" name=\"nuTUser\" placeholder=\"Enter new username...\"><br>";

    //append fields
    appendElementToDisplayCol(uuser, 1);
    appendElementToDisplayCol(nufname, 1);
    appendElementToDisplayCol(nulname, 1);
    appendElementToDisplayCol(nuuser, 1);

    //remove fields
    var ruser = document.createElement("rUser"); 
    ruser.innerHTML = "<input type=\"text\" name=\"rTUser\" placeholder=\"Enter username...\"><br>";
    appendElementToDisplayCol(ruser, 2);
}
function createGraderFields(){
    //add fields
    var afname = document.createElement("aFirstName");
    afname.innerHTML = "<input type=\"text\" name=\"aGFName\" placeholder=\"Enter first name...\"><br>";

    var alname = document.createElement("aLastName");
    alname.innerHTML = "<input type=\"text\" name=\"aGLName\" placeholder=\"Enter last name...\"><br>";

    var auser = document.createElement("aUser");
    auser.innerHTML = "<input type=\"text\" name=\"aGUser\" placeholder=\"Enter username...\"><br>";
    //append fields
    appendElementToDisplayCol(afname, 0);
    appendElementToDisplayCol(alname, 0);
    appendElementToDisplayCol(auser, 0);

    //update fields
    //old info
    var uuser = document.createElement("uUser");
    uuser.innerHTML = "<input type=\"text\" name=\"uGUser\" placeholder=\"Enter current username...\"><br>";
    //new info
    var nufname = document.createElement("nuFirstName");
    nufname.innerHTML = "<input type=\"text\" name=\"nuGFName\" placeholder=\"Enter new first name...\"><br>";

    var nulname = document.createElement("nuLastName");
    nulname.innerHTML = "<input type=\"text\" name=\"nuGLName\" placeholder=\"Enter new last name...\"><br>";

    var nuuser = document.createElement("nuUser");
    nuuser.innerHTML = "<input type=\"text\" name=\"nuGUser\" placeholder=\"Enter new username...\"><br>";

    //append fields
    appendElementToDisplayCol(uuser, 1);
    appendElementToDisplayCol(nufname, 1);
    appendElementToDisplayCol(nulname, 1);
    appendElementToDisplayCol(nuuser, 1);

    //remove fields
    var ruser = document.createElement("rUser"); 
    ruser.innerHTML = "<input type=\"text\" name=\"rGUser\" placeholder=\"Enter username...\"><br>";
    appendElementToDisplayCol(ruser, 2);
}
function createStudentFields(){
    //add fields
    var auser = document.createElement("aUser");
    auser.innerHTML = "<input type=\"text\" name=\"aSUser\" placeholder=\"Enter username...\"><br>";

    appendElementToDisplayCol(auser, 0);
    //update fields
    //old info
    var uuser = document.createElement("uUser");
    uuser.innerHTML = "<input type=\"text\" name=\"uSUser\" placeholder=\"Enter current username...\"><br>";

    var nuuser = document.createElement("nuUser");
    nuuser.innerHTML = "<input type=\"text\" name=\"nuSUser\" placeholder=\"Enter new username...\"><br>";

    appendElementToDisplayCol(uuser, 1);
    appendElementToDisplayCol(nuuser, 1);
    //remove fields
    var ruser = document.createElement("rUser"); 
    ruser.innerHTML = "<input type=\"text\" name=\"rDUser\" placeholder=\"Enter username...\"><br>";
    appendElementToDisplayCol(ruser, 2);
}
function createSectionFields(){
    //add fields
    var tuser = document.createElement("tUser");
    tuser.innerHTML = "<input type=\"text\" name=\"teacherOfSection\" placeholder=\"Enter teacher username...\"><br>";
    var guser = document.createElement("gUser");
    guser.innerHTML = "<input type=\"text\" name=\"graderOfSection\" placeholder=\"Enter grader username...\"><br>";
    var section = document.createElement("section");
    section.innerHTML = "<input type=\"text\" name=\"section\" placeholder=\"Section number...\"><br>";
    appendElementToDisplayCol(tuser, 0);
    appendElementToDisplayCol(guser, 0);
    appendElementToDisplayCol(section, 0);
    //update fields
    var section = document.createElement("section");
    section.innerHTML = "<input type=\"text\" name=\"usection\" placeholder=\"Section number to update...\"><br><br>";
    appendElementToDisplayCol(section, 1);

    var tuser = document.createElement("tUser");
    tuser.innerHTML = "<input type=\"text\" name=\"uteacher\" placeholder=\"Enter teacher username...\"><br>";
    appendElementToDisplayCol(tuser, 1);
    update = document.createElement("updateSubmit");
    update.innerHTML = "<input type=\"submit\" value=\"update\" name=\"updateSectionTeacher\"></input><br>"
    appendElementToDisplayCol(update, 1);

    var guser = document.createElement("gUser");
    guser.innerHTML = "<input type=\"text\" name=\"ugrader\" placeholder=\"Enter grader username...\"><br>";
    appendElementToDisplayCol(guser, 1);
    update = document.createElement("updateSubmit2");
    update.innerHTML = "<input type=\"submit\" value=\"update\" name=\"updateSectionGrader\"></input><br>"
    appendElementToDisplayCol(update, 1);

    var suser = document.createElement("sUser");
    suser.innerHTML = "<input type=\"text\" name=\"ustudent\" placeholder=\"Enter student username...\"><br>";
    appendElementToDisplayCol(suser, 1);
    update = document.createElement("addStudent");
    update.innerHTML = "<input type=\"submit\" value=\"update\" name=\"updateSectionStudent\"></input><br>"
    appendElementToDisplayCol(update, 1);
    
    //remove fields
    var section = document.createElement("section");
    section.innerHTML = "<input type=\"text\" name=\"rsection\" placeholder=\"Section number to remove...\"><br>";
    appendElementToDisplayCol(section, 2);
    remove = document.createElement("removeSubmit");
    remove.innerHTML = "<input type=\"submit\" value=\"remove\" name=\"removeSection2\"></input><br><br><br>"
    appendElementToDisplayCol(remove, 2);

    var section2 = document.createElement("sectionToRemoveFrom");
    section2.innerHTML = "<input type=\"text\" name=\"sectionToRemoveFrom\" placeholder=\"Section to remove from...\"><br>";
    appendElementToDisplayCol(section2, 2);
   
    var grader = document.createElement("GraderToRemove");
    grader.innerHTML = "<input type=\"text\" name=\"gUser\" placeholder=\"Grader Username\"><br>";
    appendElementToDisplayCol(grader, 2);
   
    var student = document.createElement("studentToRemove");
    student.innerHTML = "<input type=\"text\" name=\"sUser\" placeholder=\"Student Username\"><br>";
    appendElementToDisplayCol(student, 2);
}
function createMiscMenu(){
    //Lets reuse already created elements
    //drop all
    var dropNode = document.getElementById("addColumn");
    dropNode.innerHTML = "Drop all tables<br>";
    //truncate database
    var truncateNode = document.getElementById("updateColumn");
    truncateNode.innerHTML = "Truncate all tables<br>";
    //create database
    var createNode = document.getElementById("removeColumn");
    createNode.innerHTML = "Create all tables<br>";
    //dump database
    var dumpNode = document.getElementById("dumpColumn");
    dumpNode.innerHTML = "Dump all tables<br>";
    
    var drop = document.createElement("dropSubmit");
    drop.innerHTML = "<input type=\"submit\" value=\"drop\" name=\"dropAll\"></input><br><br><br>"

    var truncate = document.createElement("truncateSubmit");
    truncate.innerHTML = "<input type=\"submit\" value=\"truncate\" name=\"truncateAll\"></input><br><br><br>"

    var create = document.createElement("createSubmit");
    create.innerHTML = "<input type=\"submit\" value=\"create\" name=\"createAll\"></input><br><br><br>"

    var dump = document.createElement("dumpSubmit");
    dump.innerHTML = "<input type=\"submit\" value=\"dump\" name=\"dumpAll\"></input><br><br><br>"

    appendElementToDisplayCol(drop, 0);
    appendElementToDisplayCol(truncate, 1);
    appendElementToDisplayCol(create, 2);
    appendElementToDisplayCol(dump, 3);

    //create the text fields for misc commands
    //Output section by teacher to text
    var sectionbyT = document.createElement("sectionTField");
    sectionbyT.innerHTML = "Output section by Teacher <br><input type=\"text\" name=\"exportTSection\" placeholder=\"Enter Teacher...\"></input><br>";
    //Output section by grader to text
    var sectionbyG = document.createElement("sectionGField");
    sectionbyG.innerHTML = "Output section by Grader <br><input type=\"text\" name=\"exportGSection\" placeholder=\"Enter grader...\"></input><br>";
    //Import for text file
    var textFileField = document.createElement("textFileField");
    textFileField.innerHTML = "Import text file <br><input type=\"text\" name=\"importText\" placeholder=\"Enter text file...\"></input><br>";
    appendElementToDisplayCol(sectionbyT, 0);
    appendElementToDisplayCol(sectionbyG, 1);
    appendElementToDisplayCol(textFileField, 2);

    //Output section by teacher to text submit
    var subSectionbyT = document.createElement("subsectionTField");
    subSectionbyT.innerHTML = "<input type=\"submit\" name=\"submitExportTSection\" value=\"Export\"></input><br>";
    //Output section by grader to text submit
    var subSectionbyG = document.createElement("subsectionGField");
    subSectionbyG.innerHTML = "<input type=\"submit\" name=\"submitExportGSection\" value=\"Export\"></input><br>";
    //Import submit button
    var subTextFileField = document.createElement("subtextFileField");
    subTextFileField.innerHTML = "<input type=\"submit\" name=\"submitImportText\" value=\"Import\"></input><br>";
    
    appendElementToDisplayCol(subSectionbyT, 0);
    appendElementToDisplayCol(subSectionbyG, 1);
    appendElementToDisplayCol(subTextFileField, 2);
}
function createButtons(clickedElement){

    //create 4 submit buttons
    //For add, update, remove, dump
    var add = document.createElement("addSubmit");
    add.innerHTML = "<input type=\"submit\" value=\"add\" name=\"add" + clickedElement + "\"></input>"
    add.name="add" + clickedElement;

    var update = document.createElement("updateSubmit");
    update.innerHTML = "<input type=\"submit\" value=\"update\" name=\"update" + clickedElement + "\"></input>"
    update.name = "update" + clickedElement;

    var remove = document.createElement("removeSubmit");
    remove.innerHTML = "<input type=\"submit\" value=\"remove\" name=\"remove" + clickedElement + "\"></input>"
    remove.name = "remove" + clickedElement;

    var dump =  document.createElement("dumpSubmit");
    dump.innerHTML = "<input type=\"submit\" value=\"dump\" name=\"dump" + clickedElement + "\"></input>"

    appendElementToDisplayCol(add, 0);
    if(clickedElement != "Section")
        appendElementToDisplayCol(update, 1);
    appendElementToDisplayCol(remove, 2);
    appendElementToDisplayCol(dump, 3);
}
function appendElementToDisplayCol(button, column){
    console.log(button);
    var element;
    switch(column){
        case 0:
            element = document.getElementById("addColumn");
            break;
        case 1:
            element = document.getElementById("updateColumn");
            break;
        case 2:
            element = document.getElementById("removeColumn");
            break;

        case 3:
            element = document.getElementById("dumpColumn");
            break;
        default:
            console.log("Tried to append to Illegal column!");
            break;
    }
    if(!element){
        console.log("Element null?");
        return false;
    }
    console.log("Appending element!");
    console.log(element);
    element.appendChild(button);
    return true;
}
function clearMainDiv(){
    const myNode = document.getElementById("displayDiv");
    while (myNode.firstChild) {
        myNode.removeChild(myNode.firstChild);
    }
}