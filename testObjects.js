sections = Array();
section1 = {
    number : 44300,
    teacher :   {
        firstname : "Tim",
        lastname : "hoffman",
        email : "hoffmant@pitt.edu"
    },
    grader : {
        firstname : "test",
        lastname : "testname",
        email : "test@pitt.edu"
    },
    students : ["tst32", "plo45", "lit49", "tko95", "tms90", "elp43", "rmn433", "men459", "rmi54", "fnd33", "wmn495", "fem95", "plo99", "rew23", "sdf43"]
};
section = {
    number : 45938,
    teacher : {
        firstname : "stete",
        lastname : "hoftfmasetn",
        email : "hoadsfgant@pitt.edu"
    },
    grader : {
        firstname : "testgrader2",
        lastname : "graderlastname2",
        email : "test222@pitt.edu"
    },
    students : [""]
};
section.students.push("plloo");
for(var i = 0; i < 60; i++){
    section.students.push("plo" + i);
}
sections.push(section1);
sections.push(JSON.parse(JSON.stringify(section)));
section.number = 34243;
section.students = [];
student = "tes";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 34633;
section.students = [];
student = "ccs";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 34243;
section.students = [];
student = "erw";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 34143;
section.students = [];
student = "tre";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 35643;
section.students = [];
student = "yui";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 44643;
section.students = [];
student = "oui";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 64643;
section.students = [];
student = "zcd";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 84643;
section.students = [];
student = "erf";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 94643;
section.students = [];
student = "gef";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 74643;
section.students = [];
student = "gar";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))

section.teacher = {
    firstname: "new",
    lastname : "teach",
    email : "newt@pitt.edu"
};
section.grader = {
    firstname: "new",
    lastname : "grader",
    email : "newgr@pitt.edu"
};
section.number = 974647;
section.students = [];
student = "lar";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 984647;
section.students = [];
student = "war";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 964647;
section.students = [];
student = "ear";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 954647;
section.students = [];
student = "rar";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 944647;
section.students = [];
student = "tar";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
section.number = 934647;
section.students = [];
student = "mar";
for(var i = 0; i < 60; i++){
    section.students.push(student + i);
}
sections.push(JSON.parse(JSON.stringify(section)))
console.log(JSON.stringify(sections));