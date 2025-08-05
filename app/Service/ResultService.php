<?php

namespace App\Service;

use App\Models\Result;
use App\Models\SubjectMark;

class ResultService
{
    /**
     * Create a new class instance.
     */
    public function addSubjectMarks($data,$studentId,$exampType,$resultId)
    {
        
        foreach($data as $subjectId => $details){
            SubjectMark::create([
                'result_id' => $resultId,
                'subject_id' => $subjectId,
                'student_id' => $studentId,
                'total_mark'=> $details['total'],
                'obtained_mark' => $details['obtained'],
                'exam_type' => $exampType,
                'grade' => $details['grade']    
            ]);
        }

        return true;
    }

    public function create($data){
        return Result::create([
            'class_id' => $data['class_id'],
            'student_id' => $data['student_id'],
            'total_mark' => $data['total_marks'],
            'obtained_mark' => $data['obtained_marks'],
            'exam_type' => $data['exam_type'],
            'exam_date' => $data['exam_date'],
            'grade' => $data['overall_grade'],
            'result_status' => $data['result_status'],
        ]);
    }

    public function update($data,$result){
        return $result->update([
            'total_mark' => $data['total_marks'],
            'obtained_mark' => $data['obtained_marks'],
            'exam_type' => $data['exam_type'],
            'exam_date' => $data['exam_date'],
            'grade' => $data['overall_grade'],
            'result_status' => $data['result_status'],
        ]);
    }

    public function updateSubjectMarks($data,$exampType,$resultId){
         foreach($data as $subjectId => $details){
            SubjectMark::where('subject_id',$subjectId)
                        ->where('result_id',$resultId)
                        ->update([
                        // 'subject_id' => $subjectId,
                        'result_id' => $resultId,
                        'subject_id' => $subjectId,
                        'total_mark'=> $details['total'],
                        'obtained_mark' => $details['obtained'],
                        'exam_type' => $exampType,
                        'grade' => $details['grade']    
                    ]);
        }
        return true;
    }
}