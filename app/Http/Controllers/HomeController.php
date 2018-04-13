<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactUs;
use App\Http\Requests\EmailReportRequest;
use App\Mail\ReportEmail;
use Mail;

class HomeController extends Controller 
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }


	/**
     * Show the application dashboard.
     *
     * @return Redirect
     */
    public function index()
    {
        return redirect('software');
    }


	/**
     * Show the named page
     *
     * @return \Illuminate\View\View
     */
    public function software()
    {
        return view('software');
    }
    public function events()
    {
        return view('events');
    }
    public function media()
    {
        return view('media');
    }
    public function hub()
    {
        return view('hub');
    }
    public function academy()
    {
        return view('academy');
    }
    public function community()
    {
        return view('community');
    }
    public function about()
    {
        return view('about');
    }
    public function partnerships()
    {
        return view('partnerships');
    }
    public function contact()
    {
        return view('contact');
    }

    
    /**
     * Send an email to us
     *
     * @return \Illuminate\Http\Response
     */
    public function postContact(ContactRequest $r)
    {
        // Data to be used on the email view
        $data = [
            'reply_name' => $r->get('name'),
            'reply_email' => $r->get('email'),
            // 'subject' => $r->get('subject'),
            'body' => nl2br(e($r->get('body'))),
        ];
        
        if($r->get('order') || $this->isSpam($r)){ // fake a success response
            $success = 'Email has been sent successfully!';
            return success($success);
        }
        
        try {

            Mail::send(new ContactUs($data));

            return success('Email delivered. We will get back soon!');

        } catch (\Swift_TransportException $e) {
            return error('Failed to deliver mail! \n Kindly retry later!');
        } catch (\Swift_RfcComplianceException $e) {
            return error('Failed to send mail! \n Your name does not match the email address!');
        } catch (Exception $e) {
            return error('Failed to send mail! \n Kindly retry later!');
        }

        return error('Email not delivered. Please retry later!');
    }
    public function isSpam($request){
        $body = $request->get('body');
        $subject = $request->get('subject');
        if (strpos($body, 'viagra') !== false 
         || strpos($subject, 'viagra') !== false
         || strpos($body, 'ciali') !== false 
         || strpos($subject, 'ciali') !== false
         || strpos($body, 'bestsky.info') !== false 
         || strpos($subject, 'bestsky.info') !== false
         || strpos($subject, 'SBA Capital') !== false) {
            return true;
        }
        if (strpos($body, 'SBA') !== false 
        && strpos($subject, 'Capital') !== false
        && strpos($subject, 'loan') !== false) {
            return true;
        }
        return false
    }

	/**
     * Log out the example account and redirect to registration
     *
     * @return View
     */
    public function join() {
        auth()->logout();
        return redirect('register');
    }

    public function emptySearch(){
        return redirect('search/kcpe'); // default press
    }

    public function search(){

        $query = request('query');
        
        if($query == 'Search...') return redirect('question'); // default press

        $query = str_replace('%20', '_', $query); // replace space with underscores
        $query = str_replace(' ', '_', rawurldecode($query)); // replace space with underscores

        return redirect()->route('search.get', compact('query'));
    }

    public function getSearch($query){

        $query = str_replace('_', ' ', $query);

        $quizzes = \App\Quiz::
            where('publicity', '!=', 'private')->where('title', 'like', '%'.$query.'%')
            ->orWhere('publicity', '!=', 'private')->where('introduction', 'like', '%'.$query.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        // return $quizzes;

        $questions = \App\Question::
            join('quizzes', function($join){
                $join->on('questions.quiz_id', '=', 'quizzes.id')
                ->where('quizzes.publicity', '!=', 'private');
            })
            ->where('questions.number', 'like', '%'.$query.'%')
            ->orWhere('questions.ask', 'like', '%'.$query.'%')
            ->orWhere('questions.answer', 'like', '%'.$query.'%')
            ->orderBy('questions.created_at', 'desc')
            ->paginate(10);

        // return $questions;

        $title = 'Searched: '.$query;
        return view('searched', compact('title', 'quizzes', 'questions', 'query'));
    }
}