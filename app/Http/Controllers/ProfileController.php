<?php

namespace App\Http\Controllers;

use App\Contracts\Interfaces\AuthorInterface;
use App\Contracts\Interfaces\CategoryInterface;
use App\Contracts\Interfaces\FollowerInterface;
use App\Contracts\Interfaces\NewsCategoryInterface;
use App\Contracts\Interfaces\NewsHasLikeInterface;
use App\Contracts\Interfaces\NewsInterface;
use App\Contracts\Interfaces\NewsPhotoInterface;
use App\Contracts\Interfaces\NewsSubCategoryInterface;
use App\Contracts\Interfaces\NewsTagInterface;
use App\Contracts\Interfaces\SubCategoryInterface;
use App\Contracts\Interfaces\TagInterface;
use App\Contracts\Interfaces\UserInterface;
use App\Enums\NewsStatusEnum;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\NewsRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Category;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsHasLike;
use App\Models\NewsPhoto;
use App\Models\NewsSubCategory;
use App\Models\NewsTag;
use App\Models\SubCategory;
use App\Models\User;
use App\Services\NewsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    private UserInterface $user;
    private NewsInterface $news;
    private FollowerInterface $followers;
    private NewsHasLikeInterface $newsHasLike;
    private AuthorInterface $author;
    private SubCategoryInterface $subCategory;
    private NewsService $NewsService;
    private CategoryInterface $category;
    private TagInterface $tag;

    private NewsPhotoInterface $newsPhoto;
    private NewsCategoryInterface $newsCategory;
    private NewsSubCategoryInterface $newsSubCategory;
    private NewsTagInterface $newsTag;

    public function __construct(NewsHasLikeInterface $newsHasLike, FollowerInterface $followers, TagInterface $tag, NewsTagInterface $newsTag, NewsSubCategoryInterface $newsSubCategory, NewsCategoryInterface $newsCategory, UserInterface $user, AuthorInterface $author, NewsInterface $news,SubCategoryInterface $subCategory, NewsService $NewsService, CategoryInterface $category, NewsPhotoInterface $newsPhoto)
    {
        $this->newsCategory = $newsCategory;
        $this->newsTag = $newsTag;
        $this->newsSubCategory = $newsSubCategory;

        $this->newsHasLike = $newsHasLike;
        $this->followers = $followers;
        $this->tag = $tag;
        $this->user = $user;
        $this->news = $news;
        $this->author = $author;
        $this->newsPhoto = $newsPhoto;
        $this->subCategory = $subCategory;
        $this->category = $category;
        $this->NewsService = $NewsService;

    }

    public function index(Request $request)
    {
        $subCategories = $this->subCategory->get();
        $category = $this->category->get();
        $news = $this->news->search($request)->where('author_id', auth()->user()->author->id)->wherein('status', "active");

        $news_panding = $this->news->getAll()->where('author_id', auth()->user()->author->id)->wherein('status', "panding")->count();
        $news_active = $this->news->getAll()->where('author_id', auth()->user()->author->id)->wherein('status', "active")->count();
        $news_reject = $this->news->getAll()->where('author_id', auth()->user()->author->id)->wherein('status', "nonactive")->count();

        $news_post = $this->news->getAll()->where('author_id', auth()->user()->author->id)->count();
        $followers = $this->followers->get()->where('author_id', auth()->user()->author->id)->count();
        $following = $this->followers->get()->where('user_id', auth()->user()->id)->count();

        $news_id = News::where('user_id', auth()->user()->id)->pluck('id');
        $news_like = $this->newsHasLike->get()->where('news_id', $news_id)->count();

        $authors = $this->author->get();
        // return view('pages.author.index', compact('news', 'news_status'));
        return view('pages.author.index', compact('news','subCategories', 'category', 'authors', 'news_panding', 'news_active', 'news_reject', 'news_post', 'followers', 'news_like', 'following'));
    }

    public function profilestatus()
    {
        $subCategories = $this->subCategory->get();
        $news = $this->news->get();
        return view('pages.author.status.index', compact('news', 'subCategories'));
    }

    public function createberita()
    {
        $subCategories = $this->subCategory->get();
        $news = $this->news->get();
        $categories = $this->category->get();
        return view('pages.author.news.create', compact('news', 'subCategories', 'categories'));
    }

    public function store()
    {
        //
    }

    public function updateberita(NewsUpdateRequest $request, News $news, NewsPhoto $newsPhoto, NewsCategory $newsCategory, NewsSubCategory $newsSubCategory, NewsTag $newsTag)
    {
        $data = $this->NewsService->update($request, $news, $newsPhoto);
        $data['status'] = NewsStatusEnum::PANDING->value;
        $this->news->update($news->id, $data);

        if ($request->hasFile('multi_photo')) {
            $newsPhoto->where('news_id', $news->id)->delete();
            foreach ($data['multi_photo'] as $photo) {
                $newsPhoto->create([
                    'news_id' => $news->id,
                    'multi_photo' => $photo
                ]);
            }
        }

        $newsCategory->where('news_id', $news->id)->delete();
        foreach ($data['category'] as $category) {
            $this->newsCategory->store([
                'news_id' => $news->id,
                'category_id' => $category
            ]);
        }

        $newsSubCategory->where('news_id', $news->id)->delete();
        foreach ($data['sub_category'] as $subCategory) {
            $this->newsSubCategory->store([
                'news_id' => $news->id,
                'sub_category_id' => $subCategory
            ]);
        }

        $newsTag->where('news_id', $news->id)->delete();
        foreach ($data['tags'] as $tagId) {
            $this->newsTag->store([
                'news_id' => $news->id,
                'tag_id' => $tagId
            ]);
        }


        return ResponseHelper::success(null, trans('alert.add_success'));

    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function editnews($newsId)
    {
        $news = $this->news->showWithSlug($newsId);

        $subCategories = $this->subCategory->get();
        $categories = $this->category->get();
        $tags = $this->tag->get();
        $newsPhoto = $this->newsPhoto->get()->whereIn('news_id', $news);
        $newsTags = $this->newsTag->get()->whereIn('news_id', $news);
        $newsCategory = $this->newsCategory->get()->whereIn('news_id', $news);
        $newsSubCategory = $this->newsSubCategory->get()->whereIn('news_id', $news);

        return view('pages.author.news.update', compact('news','subCategories','categories','newsPhoto', 'tags', 'newsCategory', 'newsSubCategory', 'newsTags'));
    }

    public function profileupdate(){
        return view('pages.author.profile.update');
    }

    public function updateprofile(User $user, RegisterRequest $registerRequest){
        $data = $registerRequest->validated();
        $this->user->update($user->id, $data);
        return back();
    }

    public function changepassword(User $user, RegisterRequest $registerRequest){
        if (!Hash::check($registerRequest->old_password, auth()->user()->password)) {
            throw ValidationException::withMessages(['old_password' => 'Password lama tidak sesuai.']);
            return redirect()->back()->with('failed', trans('alert.password_failed'));
        }

        $this->user->update($user->id, $registerRequest->validated());
        return redirect()->back()->with('success', trans('alert.password_updated'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // public function profileupdate(){

    // }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function deletenews()
    {

    }
}
