<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\NewsletterUser;
use Excel;
use Ctct\ConstantContact;

class NewsletterController extends Controller
{

    public $view_base = 'newsletter';

    /**
     * @return ConstantContact object
     */
    private function ccObject()
    {
        return new ConstantContact(config('constantcontact.api_key'));
    }

    /**
     * @return access_token
     */
    private function cc_token()
    {
        return config('constantcontact.access_token');
    }

    /**
     * NewsletterController constructor.
     */
    public function __construct()
    {
        if (Auth::check() && !Auth::user()->hasPermission("content_management")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = NewsletterUser::orderBy('id', 'asc')->get();
        $lists = $this->showAllLists();

        return view($this->view_base . '.index')->with(compact('users'))->with(compact('lists'));
    }

    /**
     * Create New Contact list
     *
     * @return view
     */

    public function store(Request $request)
    {
        $contactList = new \Ctct\Components\Contacts\Contactlist();
        $contactList->name = $request->name;
        $contactList->status = $request->status;
        $this->ccObject()->listService->addList($this->cc_token(), $contactList);
        flash()->success('New List has been added');
        return redirect()->back();

    }

    /**
     * Remove list from CC
     *
     * @return view
     */


    public function listRemove($id)
    {
        $this->ccObject()->listService->deleteList($this->cc_token(), $id);
        flash()->error('List has been removed');
        return redirect()->back();
    }

    /**
     * @return allCC lists
     */
    public function showAllLists()
    {
        return $this->ccObject()->listService->getLists($this->cc_token());
    }

    /**
     * @return mixed
     */
    public function showAllListsView()
    {
        $lists = $this->showAllLists();

        return view($this->view_base . '.constantContact.lists')->with(compact('lists'));
    }

    /**
     * @param $id
     * @return Single Id of Email List
     */
    public function listDetailsView($id)
    {
        $list = $this->ccObject()->listService->getList($this->cc_token(), $id);
        $contacts = $this->ccObject()->contactService->getContactsFromList($this->cc_token(), $id);
        return view($this->view_base . '.constantContact.listDetails')->with(['list' => $list, 'contacts' => $contacts]);

    }

    /**
     * @return All Email Campaigns
     */
    public function showEmailCampaigns()
    {
        $campaigns = $this->ccObject()->emailMarketingService->getCampaigns($this->cc_token());
        return view($this->view_base . '.constantContact.campaigns')->with(compact('campaigns'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function showEmailCampaignsDetail($id)
    {
        $campaign = $this->ccObject()->emailMarketingService->getCampaign($this->cc_token(), $id);
        $lists = $campaign->sent_to_contact_lists;
        $lists_info = [];
        foreach ($lists as $list) {
            try {
                $list_info = $this->getListDetails($list->id);

            } catch (\Exception $e) {

            }
            $lists_info[] = $list_info;

        }
        return view($this->view_base . '.constantContact.campaignsDetail')->with(compact('campaign'))->with(compact('lists_info'));
    }

    /**
     * @param $id
     */
    public function getListDetails($id)
    {
        return $this->ccObject()->listService->getList($this->cc_token(), $id);
    }

    /**
     * Export Excel file
     *
     * @return self
     */
    public function export($type)
    {

        ini_set('memory_limit', '1G');
        $newsletterusers = NewsletterUser::orderBy('id', 'asc')->get(['email', 'created_at']);

        Excel::create('ION_Newsletter_Users-' . date('m-d-Y_H.i.s'), function ($excel) use ($newsletterusers) {
            $excel->sheet('entries', function ($sheet) use ($newsletterusers) {
                $entries = $newsletterusers;
                $columns = array_keys($entries[0]->getAttributes());
                $sheet->loadView($this->view_base . '.export', ["columns" => $columns, "entries" => $entries, "newsletterusers" => $newsletterusers]);
            });

        })->download($type);

    }

    /**
     * Remove multiple entries.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function multipleDestroy(Request $request)
    {
        NewsletterUser::whereIn('id', $request->ids)->delete();
        $count = count($request->ids);
        flash()->error((($count > 1) ? $count . ' users have' : $count . ' user has') . ' been removed');
        $data = NewsletterUser::orderBy('id', 'desc')->get();
        $results = view($this->view_base . '.index')->with(['users' => $data])->render();
        return json_encode(['success' => true, 'results' => $results]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = NewsletterUser::findOrFail($id);
        $user->delete();
        flash()->error("User <strong>" . $user->email . "</strong> has been deleted");
        return redirect()->back();
    }
}
