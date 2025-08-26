<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\SController;
use App\Http\Controllers\MockTestController;
use App\Http\Controllers\QuestionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [AuthController::class, 'loadRegister']);
Route::post('/register', [AuthController::class, 'studentRegister'])->name('studentRegister');

Route::get('/login', function () {
    return redirect('/');
});

Route::get('/', [AuthController::class, 'loadLogin']);
Route::post('/login', [AuthController::class, 'userLogin'])->name('userLogin');

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/forget-password', [AuthController::class, 'forgetPasswordLoad']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('forgetPassword');

Route::get('/reset-password', [AuthController::class, 'resetPasswordLoad']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');

Route::group(['middleware' => ['web', 'checkAdmin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::resource('admin/courses', CourseController::class);
    Route::resource('admin/mock-tests', MockTestController::class);
    Route::resource('admin/questions', QuestionController::class);
    Route::get('/admin/questions/{question}/answers', [QuestionController::class, 'getAnswers']);
    Route::get('/admin/batches', [AdminController::class, 'batches'])->name('batches');
    Route::get('/admin/batch/{id}', [AdminController::class, 'batchDetail'])->name('batchDetail');
    Route::post('/admin/enroll-student', [AdminController::class, 'enrollStudent'])->name('enrollStudent');
    Route::post('/admin/upload-pdf', [AdminController::class, 'uploadPdf'])->name('uploadPdf');
    Route::post('/admin/upload-video', [AdminController::class, 'uploadVideo'])->name('uploadVideo');
    Route::post('/admin/delete-pdf', [AdminController::class, 'deletePdf'])->name('deletePdf');
    Route::post('/admin/delete-video', [AdminController::class, 'deleteVideo'])->name('deleteVideo');
    Route::get('/admin/check-enrolled-students', [AdminController::class, 'checkEnrolledStudents'])->name('checkEnrolledStudents');

    //Batches route
    Route::post('/add-subject', [AdminController::class, 'addSubject'])->name('addSubject');
    Route::post('/edit-subject', [AdminController::class, 'editSubject'])->name('editSubject');
    Route::post('/delete-subject', [AdminController::class, 'deleteSubject'])->name('deleteSubject');
    Route::post('/delete-query', [AdminController::class, 'deleteqery'])->name('deleteqery');
    ///PFD save
    Route::post('/add-pdf', [AdminController::class, 'addpdf'])->name('addpdf');
    Route::post('/add-link', [AdminController::class, 'addlink'])->name('addlink');
    Route::get('pdf/{id}', [AdminController::class, 'showPdf'])->name('pdf.show');
    Route::get('link/{id}', [AdminController::class, 'showlink'])->name('link.show');
    Route::get('/deletepdf/{id}', [AdminController::class, 'deletepdf'])->name('delete.pdf');
    Route::get('/deletelink/{id}', [AdminController::class, 'deletelink'])->name('delete.link');

    Route::get('/fetch-pdf', [AdminController::class, 'fetchData'])->name('fetch.data');

    //exam route
    Route::get('/admin/exam', [AdminController::class, 'examDashboard']);
    Route::post('/add-exam', [AdminController::class, 'addExam'])->name('addExam');
    Route::get('/get-exam-detail/{id}', [AdminController::class, 'getExamDetail'])->name('getExamDetail');
    Route::post('/update-exam', [AdminController::class, 'updateExam'])->name('updateExam');
    Route::post('/delete-exam', [AdminController::class, 'deleteExam'])->name('deleteExam');
    /// Show Question Subject Wise
    Route::get('/pfmptest1', [AdminController::class, 'pfmptest1'])->name('pfmp.test1');
    Route::get('/pfmptest2', [AdminController::class, 'pfmptest2'])->name('pfmp.test2');
    Route::get('/pfmptest3', [AdminController::class, 'pfmptest3'])->name('pfmp.test3');
    Route::get('/pfmptest4', [AdminController::class, 'pfmptest4'])->name('pfmp.test4');
    Route::get('/pfmptest5', [AdminController::class, 'pfmptest5'])->name('pfmp.test5');
    Route::get('/pfmptest6', [AdminController::class, 'pfmptest6'])->name('pfmp.test6');
    Route::get('/pfmptest7', [AdminController::class, 'pfmptest7'])->name('pfmp.test7');
    //////PGMP Question Show
    Route::get('/pgmptest1', [AdminController::class, 'pgmptest1'])->name('pgmp.test1');
    Route::get('/pgmptest2', [AdminController::class, 'pgmptest2'])->name('pgmp.test2');
    Route::get('/pgmptest3', [AdminController::class, 'pgmptest3'])->name('pgmp.test3');
    Route::get('/pgmptest4', [AdminController::class, 'pgmptest4'])->name('pgmp.test4');
    Route::get('/pgmptest5', [AdminController::class, 'pgmptest5'])->name('pgmp.test5');
    Route::get('/pgmptest6', [AdminController::class, 'pgmptest6'])->name('pgmp.test6');
    Route::get('/pgmptest7', [AdminController::class, 'pgmptest7'])->name('pgmp.test7');
    /////PMP Question Show
    Route::get('/pmptest1', [AdminController::class, 'pmptest1'])->name('pmp.test1');
    Route::get('/pmptest2', [AdminController::class, 'pmptest2'])->name('pmp.test2');
    Route::get('/pmptest3', [AdminController::class, 'pmptest3'])->name('pmp.test3');
    Route::get('/pmptest4', [AdminController::class, 'pmptest4'])->name('pmp.test4');
    Route::get('/pmptest5', [AdminController::class, 'pmptest5'])->name('pmp.test5');
    Route::get('/pmptest6', [AdminController::class, 'pmptest6'])->name('pmp.test6');
    Route::get('/pmptest7', [AdminController::class, 'pmptest7'])->name('pmp.test7');
    ////PMI-ACP Question Show
    Route::get('/pmiacptest1', [AdminController::class, 'pmiacptest1'])->name('pmiacp.test1');
    Route::get('/pmiacptest2', [AdminController::class, 'pmiacptest2'])->name('pmiacp.test2');
    Route::get('/pmiacptest3', [AdminController::class, 'pmiacptest3'])->name('pmiacp.test3');
    Route::get('/pmiacptest4', [AdminController::class, 'pmiacptest4'])->name('pmiacp.test4');
    Route::get('/pmiacptest5', [AdminController::class, 'pmiacptest5'])->name('pmiacp.test5');
    Route::get('/pmiacptest6', [AdminController::class, 'pmiacptest6'])->name('pmiacp.test6');
    Route::get('/pmiacptest7', [AdminController::class, 'pmiacptest7'])->name('pmiacp.test7');
    ////PMI_RMP Question show
    Route::get('/pmirmptest1', [AdminController::class, 'pmirmptest1'])->name('pmirmp.test1');
    Route::get('/pmirmptest2', [AdminController::class, 'pmirmptest2'])->name('pmirmp.test2');
    Route::get('/pmirmptest3', [AdminController::class, 'pmirmptest3'])->name('pmirmp.test3');
    Route::get('/pmirmptest4', [AdminController::class, 'pmirmptest4'])->name('pmirmp.test4');
    Route::get('/pmirmptest5', [AdminController::class, 'pmirmptest5'])->name('pmirmp.test5');
    Route::get('/pmirmptest6', [AdminController::class, 'pmirmptest6'])->name('pmirmp.test6');
    Route::get('/pmirmptest7', [AdminController::class, 'pmirmptest7'])->name('pmirmp.test7');
    //Q&A Routes
    Route::get('/admin/qna-ans', [AdminController::class, 'qnaDashboard']);
    Route::get('/admin/flash', [AdminController::class, 'flashDashboard'])->name('flash-cards.index');
    Route::get('/admin/flash-cards/{course_id}', [AdminController::class, 'showFlashQuestions'])->name('flash-cards.show');
    Route::get('/admin/flash-cards/{course_id}/create', [AdminController::class, 'createFlashQuestion'])->name('flash-cards.create');
    Route::post('/admin/flash-cards', [AdminController::class, 'storeFlashQuestion'])->name('flash-cards.store');
    Route::get('/admin/flash-cards/{id}/edit', [AdminController::class, 'editFlashQuestion'])->name('flash-cards.edit');
    Route::put('/admin/flash-cards/{id}', [AdminController::class, 'updateFlashQuestion'])->name('flash-cards.update');
    Route::delete('/admin/flash-cards/{id}', [AdminController::class, 'destroyFlashQuestion'])->name('flash-cards.destroy');
    Route::post('/add-qna-ans', [AdminController::class, 'addQna'])->name('addQna');
    Route::post('/add-flash', [AdminController::class, 'addflash'])->name('addflash');
    ///edit flash cards
    Route::get('/edit-flash/{id}', [RecordController::class, 'editflash'])->name('edit.flash');
    Route::post('/update-flash/{id}', [RecordController::class, 'updatequestion'])->name('update.question');
    Route::get('/deleteflash/{id}', [RecordController::class, 'destroy'])->name('delete.flash');
    ////// Show flash card with subject
    Route::get('/pfmpflash', [RecordController::class, 'pfmpflash'])->name('pfmp.flash');
    Route::get('/pgmpflash', [RecordController::class, 'pgmpflash'])->name('pgmp.flash');
    Route::get('/pmpflash', [RecordController::class, 'pmpflash'])->name('pmp.flash');
    Route::get('/pmiacpflash', [RecordController::class, 'pmiacpflash'])->name('pmiacp.flash');
    Route::get('/pmirmpflash', [RecordController::class, 'pmirmpflash'])->name('pmirmp.flash');
    Route::get('/admin/studentquery', [AdminController::class, 'studentquery']);
    Route::get('/Studentdetails/{id}', [AdminController::class, 'Studentdetails'])->name('student.details');

    //////
    Route::get('/get-qna-details', [AdminController::class, 'getQnaDetails'])->name('getQnaDetails');
    Route::get('/delete-ans', [AdminController::class, 'deleteAns'])->name('deleteAns');
    Route::post('/update-qna-ans', [AdminController::class, 'updateQna'])->name('updateQna');
    Route::post('/delete-qna-ans', [AdminController::class, 'deleteQna'])->name('deleteQna');
    Route::post('/import-qna-ans', [AdminController::class, 'importQna'])->name('importQna');

    //Students routing
    Route::get('/admin/students', [AdminController::class, 'studentsDashboard'])->name('admin.studentsDashboard');
    Route::post('/add-student', [AdminController::class, 'addStudent'])->name('addStudent');
    Route::post('/edit-student', [AdminController::class, 'editStudent'])->name('editStudent');
    Route::post('/delete-student', [AdminController::class, 'deleteStudent'])->name('deleteStudent');
    Route::get('/export-students', [AdminController::class, 'exportStudents'])->name('exportStudents');

    //qna exams rounting
    Route::get('/get-questions', [AdminController::class, 'getQuestions'])->name('getQuestions');
    Route::post('/add-questions', [AdminController::class, 'addQuestions'])->name('addQuestions');
    Route::get('/get-exam-questions', [AdminController::class, 'getExamQuestions'])->name('getExamQuestions');
    Route::get('/delete-exam-questions', [AdminController::class, 'deleteExamQuestions'])->name('deleteExamQuestions');

    /////////////Fatch students for exam access
    Route::get('/get-students', [AdminController::class, 'getStudent'])->name('getStudent');
    Route::post('/add-students', [AdminController::class, 'addStudents'])->name('addStudents');
    Route::get('/get-student-name', [AdminController::class, 'getStudentsee'])->name('getStudentsee');

    //exam marks routes
    Route::get('/admin/marks', [AdminController::class, 'loadMarks']);
    Route::post('/update-marks', [AdminController::class, 'updateMarks'])->name('updateMarks');

    //exam review routes
    Route::get('/admin/review-exams', [AdminController::class, 'reviewExams'])->name('reviewExams');
    Route::get('/get-reviewed-qna', [AdminController::class, 'reviewQna'])->name('reviewQna');

    Route::post('/approved-qna', [AdminController::class, 'approvedQna'])->name('approvedQna');

    //crud packages
    Route::get('/admin/dashboard-package', [AdminController::class, 'loadPackageDashboard'])->name('packageDashboard');
    Route::post('/add-package', [AdminController::class, 'addPackage'])->name('addPackage');
    Route::get('/delete-package', [AdminController::class, 'deletePackage'])->name('deletePackage');
    Route::post('/edit-package', [AdminController::class, 'editPackage'])->name('editPackage');


    //payment details
    Route::get('/payment-details', [AdminController::class, 'paymentDetails'])->name('paymentDetails');
});

Route::group(['middleware' => ['web', 'checkStudent']], function () {
    Route::get('/dashboard', [AuthController::class, 'loadDashboard']);
    Route::get('/exam/{id}', [ExamController::class, 'loadExamDashboard']);

    Route::get('/Mocktest/{testsubject}/{title}', [SController::class, 'mocktest'])->name('mock.test');
    Route::get('/result', [SController::class, 'showResult']);
    Route::get('/mockunattempt/{attempt_id}/{title}/{subject}', [SController::class, 'mockunattempt'])->name('mock.unattempt');
    Route::get('/mockreview/{attempt_id}/{title}/{subject}', [SController::class, 'mockreview'])->name('mock.review');
    Route::get('/testreview', [SController::class, 'testreview1'])->name('review.test1');
    Route::get('/testreview2', [SController::class, 'testreview2'])->name('review.test2');
    Route::get('/testreview3', [SController::class, 'testreview3'])->name('review.test3');
    Route::get('/testreview4', [SController::class, 'testreview4'])->name('review.test4');
    Route::get('/testreview5', [SController::class, 'testreview5'])->name('review.test5');
    Route::get('/studypdf', [SController::class, 'studypdf'])->name('study.pdf');
    Route::get('/studyvideo', [SController::class, 'studyvideo'])->name('study.video');
    Route::get('/Mocktest1', [SController::class, 'mocktest1'])->name('mock.test1');
    Route::get('/Mocktest2', [SController::class, 'mocktest2'])->name('mock.test2');
    Route::get('/Mocktest3', [SController::class, 'mocktest3'])->name('mock.test3');
    Route::get('/Mocktest4', [SController::class, 'mocktest4'])->name('mock.test4');
    Route::get('/Mocktest5', [SController::class, 'mocktest5'])->name('mock.test5');
    Route::get('/Mocktest6', [SController::class, 'mocktest6'])->name('mock.test6');
    Route::get('/Mocktest7', [SController::class, 'mocktest7'])->name('mock.test7');
    Route::get('/Flashcard', [SController::class, 'flashcard'])->name('flash.card');
    Route::get('/Studentquery', [SController::class, 'querytext'])->name('query.text');
    Route::post('/studentQuery', [SController::class, 'studentQuery'])->name('studentQuery');
    Route::get('/load-next-section', [SController::class, 'loadNextSection'])->name('loadNextSection');
});

Route::group(['middleware' => ['checkStudent']], function () {
    Route::get('/student/mock-tests', [App\Http\Controllers\StudentController::class, 'mockTests'])->name('student.mock.tests');
    Route::get('/student/mock-test/{id}/questions', [App\Http\Controllers\StudentController::class, 'mockTestQuestions'])->name('student.mock.test.questions');
    Route::get('/student/mock-test/{id}', [App\Http\Controllers\StudentController::class, 'mockTestDetails'])->name('student.mock.test.details');
    Route::post('/student/mock-test/attempt', [App\Http\Controllers\StudentController::class, 'mockTestAttempt'])->name('student.mock.test.attempt');
    Route::post('/student/mock-test/question', [App\Http\Controllers\StudentController::class, 'mockTestQuestion'])->name('student.mock.test.question');
    Route::post('/student/mock-test/submit', [App\Http\Controllers\StudentController::class, 'mockTestSubmit'])->name('student.mock.test.submit');
    Route::get('/student/mock-test/result/{id}', [App\Http\Controllers\StudentController::class, 'mockTestResult'])->name('student.mock.test.result');
    Route::get('/student/mock-test/invoice/{id}', [App\Http\Controllers\StudentController::class, 'mockTestInvoice'])->name('student.mock.test.invoice');
    Route::get('/student/mock-test/payment/{id}', [App\Http\Controllers\StudentController::class, 'mockTestPayment'])->name('student.mock.test.payment');
    Route::post('/student/mock-test/payment/{id}', [App\Http\Controllers\StudentController::class, 'mockTestPaymentStore'])->name('student.mock.test.payment.store');
    Route::get('/student/mock-test/payment/success', [App\Http\Controllers\StudentController::class, 'mockTestPaymentSuccess'])->name('student.mock.test.payment.success');
    Route::get('/student/mock-test/payment/cancel', [App\Http\Controllers\StudentController::class, 'mockTestPaymentCancel'])->name('student.mock.test.payment.cancel');
});
