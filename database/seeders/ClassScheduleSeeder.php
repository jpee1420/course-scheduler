<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            ['id'=>1,'course_code'=>'CCS111','course_name'=>'Introduction to Computing','lab'=>0],
            ['id'=>2,'course_code'=>'CCS112','course_name'=>'Logic Design and Digital Computing','lab'=>0],
            ['id'=>3,'course_code'=>'CCS121L','course_name'=>'Fundamentals of Problem Solving and Computing','lab'=>1],
            ['id'=>4,'course_code'=>'CCS122','course_name'=>'Computer Organization','lab'=>0],
            ['id'=>5,'course_code'=>'CCS123','course_name'=>'PC Troubleshooting and Maintenance','lab'=>0],
            ['id'=>6,'course_code'=>'CCS131','course_name'=>'Technopreneurship','lab'=>0],
            ['id'=>7,'course_code'=>'CCS211L','course_name'=>'Programming 2','lab'=>1],
            ['id'=>8,'course_code'=>'CCS212','course_name'=>'Presentation Skills and Technical Writing','lab'=>0],
            ['id'=>9,'course_code'=>'CCS213L','course_name'=>'Hardware Technologies','lab'=>1],
            ['id'=>10,'course_code'=>'CCS214','course_name'=>'Data Communication and Networking','lab'=>0],
            ['id'=>11,'course_code'=>'CCS221','course_name'=>'Web Design and Development','lab'=>0],
            ['id'=>12,'course_code'=>'CCS222','course_name'=>'Operating Systems','lab'=>0],
            ['id'=>13,'course_code'=>'CCS223','course_name'=>'Information Systems Security','lab'=>0],
            ['id'=>14,'course_code'=>'CCS224L','course_name'=>'SQL Scripting','lab'=>1],
            ['id'=>15,'course_code'=>'CCS225','course_name'=>'Graphic Design','lab'=>0],
            ['id'=>16,'course_code'=>'CCS231','course_name'=>'Human and Computer Interaction','lab'=>0],
            ['id'=>17,'course_code'=>'CCS311','course_name'=>'Data Structures and Alghorithm','lab'=>0],
            ['id'=>18,'course_code'=>'CCS312','course_name'=>'Discrete Mathematics','lab'=>0],
            ['id'=>19,'course_code'=>'CCS313L','course_name'=>'Object-Oriented Programming','lab'=>1],
            ['id'=>20,'course_code'=>'CCS314','course_name'=>'Software Engineering','lab'=>0],
            ['id'=>21,'course_code'=>'CCS315','course_name'=>'Network Administration','lab'=>0],
            ['id'=>22,'course_code'=>'CCS321','course_name'=>'Database Administration','lab'=>0],
            ['id'=>23,'course_code'=>'CCS322L','course_name'=>'Application Development and Emerging Technologies','lab'=>1],
            ['id'=>24,'course_code'=>'CCS323','course_name'=>'Principles of Accounting and Financial Processes','lab'=>0],
            ['id'=>25,'course_code'=>'CCS324','course_name'=>'Multimedia Systems','lab'=>0],
            ['id'=>26,'course_code'=>'CCS325','course_name'=>'Project Management','lab'=>0],
            ['id'=>27,'course_code'=>'CCS326','course_name'=>'System Management','lab'=>0],
            ['id'=>28,'course_code'=>'CCS331','course_name'=>'IT Professionals and Social Issues','lab'=>0],
            ['id'=>29,'course_code'=>'CCS411','course_name'=>'Field Trips and Seminar','lab'=>0],
            ['id'=>30,'course_code'=>'CCS413','course_name'=>'Information Systems Security Administration','lab'=>0],
            ['id'=>31,'course_code'=>'CCS414L','course_name'=>'CAD Application','lab'=>1],
            ['id'=>32,'course_code'=>'CCS415','course_name'=>'Thesis A','lab'=>0],
            ['id'=>33,'course_code'=>'CCS416','course_name'=>'Language Theory and Automata','lab'=>0],
            ['id'=>34,'course_code'=>'CCS417','course_name'=>'Design and Implementation of Programming Languages','lab'=>0],
            ['id'=>35,'course_code'=>'CCS422','course_name'=>'OJT 200','lab'=>0],
            ['id'=>36,'course_code'=>'CCS423','course_name'=>'Thesis B','lab'=>0],
            ['id'=>37,'course_code'=>'GE11','course_name'=>'Purposive Communication','lab'=>0],
            ['id'=>38,'course_code'=>'GE12','course_name'=>'Ethics','lab'=>0],
            ['id'=>39,'course_code'=>'GE13','course_name'=>'The Contemporary World','lab'=>0],
            ['id'=>40,'course_code'=>'GE14','course_name'=>'Mathemcatics in the Modern World','lab'=>0],
            ['id'=>41,'course_code'=>'GE15','course_name'=>'Art Appreciation','lab'=>0],
            ['id'=>42,'course_code'=>'GE16','course_name'=>'Reading in Philippine History','lab'=>0],
            ['id'=>43,'course_code'=>'GE17','course_name'=>'Science, Technology and Society','lab'=>0],
            ['id'=>44,'course_code'=>'GE18','course_name'=>'Understanding the Self','lab'=>0],
            ['id'=>45,'course_code'=>'GE19','course_name'=>'Life and Works of Rizal','lab'=>0],
            ['id'=>46,'course_code'=>'GE ELec 1','course_name'=>'Living in the IT Era','lab'=>0],
            ['id'=>47,'course_code'=>'GE ELec 2','course_name'=>'Reading Visual Art','lab'=>0],
            ['id'=>48,'course_code'=>'GE ELec 3','course_name'=>'Happiness','lab'=>0],
            ['id'=>49,'course_code'=>'ELE003','course_name'=>'Intelligent Systems','lab'=>0],
            ['id'=>50,'course_code'=>'ELE004L','course_name'=>'SAP Administration','lab'=>1],
            ['id'=>51,'course_code'=>'ELE005L','course_name'=>'Data Analytics','lab'=>1],
            ['id'=>52,'course_code'=>'PE11','course_name'=>'Physical Activities, Training and Fitness 1','lab'=>0],
            ['id'=>53,'course_code'=>'PE12','course_name'=>'Physical Activities, Training and Fitness 2','lab'=>0],
            ['id'=>54,'course_code'=>'PE13','course_name'=>'Physical Activities, Training and Fitness 3','lab'=>0],
            ['id'=>55,'course_code'=>'PE14','course_name'=>'Physical Activities, Training and Fitness 4','lab'=>0],
            ['id'=>56,'course_code'=>'NSTP11','course_name'=>'National Service Training Program 1','lab'=>0],
            ['id'=>57,'course_code'=>'NSTP12','course_name'=>'National Service Training Program 2','lab'=>0],
            ['id'=>58,'course_code'=>'ELE002L','course_name'=>'Data Analytics','lab'=>1],
        ]);

        DB::table('professors')->insert([
            ['id'=>1,'name'=>'Juan Dela Cruz','profile_image'=>'placeholder.png'],
            ['id'=>2,'name'=>'TBA','profile_image'=>'placeholder.png']
        ]);

        DB::table('rooms')->insert([
            ['id'=>1,'name'=>'LR1'],
            ['id'=>2,'name'=>'LR2'],
            ['id'=>3,'name'=>'LR4'],
            ['id'=>4,'name'=>'LR5'],
            ['id'=>5,'name'=>'MM3'],
            ['id'=>6,'name'=>'Grad 1'],
            ['id'=>7,'name'=>'Grad 2'],
            ['id'=>8,'name'=>'TBA'],
            ['id'=>9,'name'=>'Gym'],
            ['id'=>10,'name'=>'Consultation'],
        ]);

        DB::table('schedules')->insert([
           
        ]);
    }
}
