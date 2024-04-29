<?php

namespace App\Http\Controllers;

use App\Contracts\Interfaces\CategoryInterface;
use App\Contracts\Interfaces\ContactUsInterface;
use App\Contracts\Interfaces\FaqInterface;
use App\Contracts\Interfaces\ReportInterface;
use App\Contracts\Interfaces\SubCategoryInterface;
use App\Enums\MessageStatusEnum;
use App\Helpers\ResponseHelper;
use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    private CategoryInterface $category;
    private SubCategoryInterface $subCategory;
    private ContactUsInterface $contactUs;
    private ReportInterface $report;

    private FaqInterface $faq;
    private User $user;

    public function __construct(ContactUsInterface $contactUs, ReportInterface $report,FaqInterface $faq, User $user, CategoryInterface $category, SubCategoryInterface $subCategory)
    {
        $this->contactUs = $contactUs;
        $this->category = $category;
        $this->subCategory = $subCategory;
        $this->report = $report;
        $this->user = $user;
        $this->faq = $faq;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactUs = $this->contactUs->get()->whereIn('status_delete', 0);
        $contactUs2 = $this->contactUs->get()->whereIn('status_delete', 0);

        $reports = $this->report->get()->whereIn('status_delete', 0);
        $reports2 = $this->report->get()->whereIn('status_delete', 0);

        $contactDelete = $this->contactUs->get()->whereIn('status_delete', 1);
        $contactDelete2 = $this->contactUs->get()->whereIn('status_delete', 1);

        $reportsDelete = $this->report->get()->whereIn('status_delete', 1);
        $reportsDelete2 = $this->report->get()->whereIn('status_delete', 1);

        $countContact = $this->contactUs->count('unread');
        $countReport = $this->report->count('unread');
        $all = $this->contactUs->countAll('unread');

        return view('pages.admin.inbox.index', compact('contactUs', 'contactUs2', 'reports', 'reports2', 'contactDelete', 'contactDelete2', 'reportsDelete', 'reportsDelete2', 'countContact', 'countReport'));
    }

    public function contact(Faq $faq){
        $contactUs = $this->contactUs->get();
        $faqs = $this->faq->get();
        $categories = $this->category->get();
        $subCategories = $this->subCategory->get();
        return view('pages.user.contact-us.index', compact('contactUs','faqs', 'categories', 'subCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactUsRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $this->contactUs->store($data);
        return back()->with('success', 'berhasil menambahkan data');
    }

    public function read(ContactUs $contact)
    {
        $data['status'] = MessageStatusEnum::READ->value;
        $this->contactUs->update($contact->id, $data);

        return ResponseHelper::success(null, trans('alert.add_success'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactUs $contact,ContactUsRequest $request)
    {
        // dd($contact->message);
        $this->contactUs->update($contact->id, $request->validated());
        return back()->with('success', 'berhasil mengupdate data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactUs $contact)
    {
        $data = [
            'status_delete' => 1
            ];

        $this->contactUs->update($contact->id, $data);
        return back()->with('success', 'berhasil menghapus data');
    }

    public function recovery(ContactUs $contact)
    {
        $data = [
            'status_delete' => 0
            ];

        $this->contactUs->update($contact->id, $data);
        return back()->with('success', 'berhasil menghapus data');
    }

    public function delete(ContactUs $contact)
    {
        $this->contactUs->delete($contact->id);
        return back()->with('success', 'berhasil menghapus data');
    }
}
