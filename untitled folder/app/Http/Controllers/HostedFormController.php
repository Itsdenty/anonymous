<?php

namespace App\Http\Controllers;

use App\Form;
use App\Theme;

class HostedFormController extends Controller
{
    //
    public function viewForm($id)
    {
        $form = Form::find($id);
        if ($form) {
            if ($form->form_type == 'popover' || $form->form_type == 'scrollbox' || $form->form_type == 'welcome_mat' || $form->form_type == 'poll' || $form->form_type == 'calculator' || $form->form_type == 'quiz' || $form->form_type == 'action' || $form->form_type == 'facebook' || $form->form_type == 'pinterest' || $form->form_type == 'twitter') {
                $current_theme = Theme::find($form->theme_id);

                $title = $form->title;

                return view('hosted.hostedForms')->with(['form' => $form, 'theme' => $current_theme, 'form_title' => $title]);
            }
        }
    }
}
