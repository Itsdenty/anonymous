<?php

use Illuminate\Database\Seeder;

class ThemeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('themes')->truncate();
        DB::table('themes')->insert([
            [
                'id' => 1,
                'name' => 'Plain',
                'form_type' => 'scrollbox',
                'content' => '<form id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#"  class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <h2 id="headline" style="text-align: center;">Join Our Newsletter</h2>
                        <p id="description" style="font-size: 16px;">Signup today for free and be the first to get notified on new updates.</p>
                        <div class="field">
                            <label id="email_label">Email</label>
                            <input name="email" id="email" type="email" placeholder="Enter your email" required />
                        </div>
                        <div id="phone_field" class="field" style="display:none">
                            <label id="phone_label">Phone</label>
                            <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                        </div>
                        <div id="gdpr_field" class="field" style="display:none">
                            <label>GDPR Agreement</label>
                            <div class="ui toggle checkbox">
                                <input id="gdpr" type="checkbox" name="gdpr">
                                <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                            </div>
                        </div>
                        <button type="submit" id="submit_button" class="ui fluid primary button">Subscribe</button>
                        <small id="footnote">And don\'t worry, we hate spam too! you can unsubscribe at anytime</small>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><strong>Powered by Sendmunk</strong></p>
                    </div>
                </form>

                <div id="success_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#"  class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <h2 id="success_headline" style="text-align: center;">Thank You</h2>
                        <p id="success_description" style="font-size: 16px;">Thank you for subscribing with us.</p>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 2,
                'name' => 'Plain',
                'form_type' => 'popover',
                'content' => '<form id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <h2 id="headline" style="text-align: center;">Join Our Newsletter</h2>
                        <p id="description" style="font-size: 16px;">Signup today for free and be the first to get notified on new updates.</p>
                        <div class="field">
                            <label id="email_label">Email</label>
                            <input  name="email"  id="email" type="email" placeholder="Enter your email" required />
                        </div>
                        <div id="phone_field" class="field" style="display:none">
                            <label id="phone_label">Phone</label>
                            <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                        </div>
                        <div id="gdpr_field" class="field" style="display:none">
                            <label>GDPR Agreement</label>
                            <div class="ui toggle checkbox">
                                <input id="gdpr" type="checkbox" name="gdpr">
                                <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                            </div>
                        </div>
                        <button type="submit" id="submit_button" class="ui fluid primary button">Subscribe</button>
                        <small id="footnote">And don\'t worry, we hate spam too! you can unsubscribe at anytime</small>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </form>

                <div id="success_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#"  class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <h2 id="success_headline" style="text-align: center;">Thank You</h2>
                        <p id="success_description" style="font-size: 16px;">Thank you for subscribing with us.</p>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 3,
                'name' => 'Plain',
                'form_type' => 'embedded',
                'content' => '<form id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <div class="content">
                        <h2 id="headline" style="text-align: center;">Join Our Newsletter</h2>
                        <p id="description" style="font-size: 16px;">Signup today for free and be the first to get notified on new updates.</p>
                        <div class="field">
                            <label id="email_label">Email</label>
                            <input  name="email"  id="email" type="email" placeholder="Enter your email" required />
                        </div>
                        <div id="phone_field" class="field" style="display:none">
                            <label id="phone_label">Phone</label>
                            <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                        </div>
                        <div id="gdpr_field" class="field" style="display:none">
                            <label>GDPR Agreement</label>
                            <div class="ui toggle checkbox">
                                <input id="gdpr" type="checkbox" name="gdpr">
                                <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                            </div>
                        </div>
                        <button type="submit" id="submit_button" class="ui fluid primary button">Subscribe</button>
                        <small id="footnote">And don\'t worry, we hate spam too! you can unsubscribe at anytime</small>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </form>

                <div id="success_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <div class="content">
                        <h2 id="success_headline" style="text-align: center;">Thank You</h2>
                        <p id="success_description" style="font-size: 16px;">Thank you for subscribing with us.</p>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 4,
                'name' => 'Plain',
                'form_type' => 'welcome_mat',
                'content' => '<form id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 100%; margin: auto; height: 100%;" class="ui form background_image">
                <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                    <h2 id="headline" style="text-align: center;">Join Our Newsletter</h2>
                    <p id="description" style="font-size: 16px;">Signup today for free and be the first to get notified on new updates.</p>
                    <div class="field">
                        <label id="email_label">Email</label>
                        <input  name="email"  id="email" type="email" placeholder="Enter your email" required />
                    </div>
                    <div id="phone_field" class="field" style="display:none">
                        <label id="phone_label">Phone</label>
                        <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                    </div>
                    <div id="gdpr_field" class="field" style="display:none">
                        <label>GDPR Agreement</label>
                        <div class="ui toggle checkbox">
                            <input id="gdpr" type="checkbox" name="gdpr">
                            <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                        </div>
                    </div>
                    <button type="submit" id="submit_button" class="ui fluid primary button">Subscribe</button>
                    <small id="footnote">And don\'t worry, we hate spam too! you can unsubscribe at anytime</small>
                </div>
            </form>
            
            <div id="success_preview" style="background: white; border-radius: 4px; padding: 25px; width: 100%; margin: auto; height: 100%;" class="ui form background_image">
                <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                    <h2 id="success_headline" style="text-align: center;">Thank You</h2>
                    <p id="success_description" style="font-size: 16px;">Thank you for subscribing with us.</p>
                </div>
            </div>
            
            <div style="position: absolute; width: 60%; left: 0; right: 0; margin: auto; bottom:20px;">
            <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
            <p style="text-align:center;"><a class="close-popup" href="#"><i class="large angle double down icon"></i></a></p>
            
            </div>
            ',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 5,
                'name' => 'Plain',
                'form_type' => 'topbar',
                'content' => '<form id="form_preview" style="margin: auto; min-height: 30px; background: #1678c2; padding:8px; text-align: center;" class="background_image">
                <a style="display:none;" class="left floated ui tiny button optin_branding" href="https://sendmunk.com" target="_blank"><strong>Powered by <img height="10px" src="https://sendmunk.com/logo/compressed_logo.png" /></strong></a>
                <a  href="#"  class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                <span style="display: inline-block;"><b id="headline" style="font-size:20px; color: white;">Join Our Newsletter</b></span>&nbsp;&nbsp;&nbsp;&nbsp;<input id="email" style="height: 2.5em; border: none; padding: 8px; width: 300px;"  name="email"  type="email" placeholder="Enter your email" required ><span id="phone_field" style="display:none"><input id="phone" style="height: 2.5em; border: none; padding: 8px; width: 300px;"  name="phone"  type="text" placeholder="Enter your phone no"  ></span>&nbsp;&nbsp;&nbsp;&nbsp;<button id="submit_button" style="color:white; background: black;" class="ui button" type="submit">Subscribe</button>
                <div id="gdpr_field" class="field" style="display:none">
                    <label>GDPR Agreement</label>
                    <div class="ui toggle checkbox">
                        <input id="gdpr" type="checkbox" name="gdpr">
                        <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                    </div>
                </div>
            </form>

            <div id="success_preview" style="margin: auto; min-height: 50px; background: #1678c2; padding:8px; text-align: center;" class="background_image" hidden>
                <a class="left floated ui tiny button optin_branding " hidden href="https://sendmunk.com" target="_blank"><strong>Powered by <img height="10px"    src="https://sendmunk.com/logo/compressed_logo.png" /></strong></a>
                <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                <b style="font-size:20px; color: white; padding: 30px; ">Thank You For Subscribing!</b>
            </div>',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 6,
                'name' => 'plain',
                'form_type' => 'poll',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <h2 id="headline" style="text-align: center;">POLL HEADING GOES HERE</h2>
                        <p id="description" style="font-size: 16px;">Poll Subheading goes here</p>
                        <button  id="start_button" class="ui fluid primary button">Click to Continue</button>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>
                <div id="success_preview" class="background_image" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;  overflow: scroll; height: 450px" hidden>
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <div class="right floated ui redp statistic">
                            <div id="percentage_votes" class="value">
                                27%
                            </div>
                            <div class="label">
                                Agrees with you
                            </div>
                        </div>
                        <h2 id="success_headline" style="text-align: center;">Poll Result Summary</h2>
                        <ol id="poll_result">
                            <li>Question 1 goes here</li>
                            <p>Option</p>
                            <div class="ui tiny blue progress" data-percent="55">
                                <div class="bar"></div>
                                <div class="label">55.00% (74 votes)</div>
                            </div>
                            <p>Option</p>
                            <div class="ui tiny blue progress" data-percent="32">
                                <div class="bar"></div>
                                <div class="label">32.00% (44 votes)</div>
                            </div>
                            <li>Question 2 goes here</li>
                            <p>Option</p>
                            <div class="ui tiny blue progress" data-percent="22">
                                <div class="bar"></div>
                                <div class="label">22.00% (31 votes)</div>
                            </div>
                            <p>Option</p>
                            <div class="ui tiny blue progress" data-percent="97">
                                <div class="bar"></div>
                                <div class="label">97.00% (135 votes)</div>
                            </div>
                        </ol>
                        <p id="success_description"></p>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>
                
                <div id="question_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" hidden>
                    <div class="content">
                        <form class="ui form">
                            <h2 style="text-align: center" id="question_header"></h2>
                            <div id="option_preview" class="grouped fields"></div> 
                            <div class="ui fluid buttons">
                                <button class="ui button question_btn" id="prev_btn" type="button">Previous</button>
                                <button class="ui button question_btn" id="next_btn" type="submit">Next</button>
                                <button class="ui button question_btn" id="finish_btn" type="button">Finish</button>
                            </div>                
                        </form>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>
                
                <div id="subscribe_preview" class="background_image" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" hidden>
                <div class="content">
                    <form id="subscribe_form" class="ui form">
                        <h2 style="text-align: center" id="footnote">Join our mailing list</h2>
                        <div class="field">
                            <label id="email_label">Email</label>
                            <input type="email" name="email" placeholder="please enter your Email" required/>
                        </div>
                        <div id="phone_field" class="field" style="display:none">
                            <label id="phone_label">Phone</label>
                            <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                        </div>
                        <div id="gdpr_field" class="field" style="display:none">
                            <label>GDPR Agreement</label>
                            <div class="ui toggle checkbox">
                                <input id="gdpr" type="checkbox" name="gdpr">
                                <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                            </div>
                        </div>
                        <div class="ui fluid buttons">
                            <button type="button" class="ui button" id="skip_btn" type="button">Skip</button>
                            <button type="submit" class="ui button" id="subscribe_btn" type="button">Subscribe</button>
                        </div>                
                    </form>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>
            </div>',
                'poll_type' => 'popover',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 7,
                'name' => 'plain',
                'form_type' => 'poll',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" class="ui form background_image">
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <h2 id="headline" style="text-align: center;">POLL HEADING GOES HERE</h2>
                        <p id="description" style="font-size: 16px;">Poll Subheading goes here</p>
                        <button  id="start_button" class="ui fluid primary button">Click to Continue</button>
                    </div>
                </div>
                <div id="success_preview" class="background_image" style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;  " hidden>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto; overflow: scroll;">
                        <h2 id="success_headline" style="text-align: center;">Poll Result Summary</h2>
                        <ol id="poll_result">
                            <li>Question 1 goes here</li>
                            <p>Option</p>
                            <div class="ui tiny blue progress" data-percent="55">
                                <div class="bar"></div>
                                <div class="label">55.00% (74 votes)</div>
                            </div>
                            <p>Option</p>
                            <div class="ui tiny blue progress" data-percent="32">
                                <div class="bar"></div>
                                <div class="label">32.00% (44 votes)</div>
                            </div>
                            <li>Question 2 goes here</li>
                            <p>Option</p>
                            <div class="ui tiny blue progress" data-percent="22">
                                <div class="bar"></div>
                                <div class="label">22.00% (31 votes)</div>
                            </div>
                            <p>Option</p>
                            <div class="ui tiny blue progress" data-percent="97">
                                <div class="bar"></div>
                                <div class="label">97.00% (135 votes)</div>
                            </div>
                        </ol>
                        <p id="success_description"></p>
                    </div>
                </div>
                <div id="subscribe_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" hidden>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <form id="subscribe_form" class="ui form">
                            <h2 style="text-align: center" id="footnote">Join our mailing list</h2>
                            <div class="field">
                                <label id="email_label">Email</label>
                                <input type="email" name="email" placeholder="please enter your Email" required/>
                            </div>
                            <div id="phone_field" class="field" style="display:none">
                                <label id="phone_label">Phone</label>
                                <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                            </div>
                            <div id="gdpr_field" class="field" style="display:none">
                                <label>GDPR Agreement</label>
                                <div class="ui toggle checkbox">
                                    <input id="gdpr" type="checkbox" name="gdpr">
                                    <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                                </div>
                            </div>
                            <div class="ui fluid buttons">
                                <button type="button" class="ui button" id="skip_btn" type="button">Skip</button>
                                <button type="submit" class="ui button" id="subscribe_btn" type="button">Subscribe</button>
                            </div>                
                        </form>
                    </div>
                </div>
                <div id="question_preview" class="background_image" style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" hidden>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <form class="ui form">
                            <h2 style="text-align: center" id="question_header"></h2>
                            <div id="option_preview" class="grouped fields"></div> 
                            <div class="ui fluid buttons">
                                <button class="ui button question_btn" id="prev_btn" type="button">Previous</button>
                                <button class="ui button question_btn" id="next_btn" type="submit">Next</button>
                                <button class="ui button question_btn" id="finish_btn" type="submit">Finish</button>
                            </div>                
                        </form>
                    </div>
                </div>
                <div style="position: absolute; width: 60%; left: 0; right: 0; margin: auto; bottom:20px;">
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    <p style="text-align:center;"><a class="close-popup" href="#"><i class="large angle double down icon"></i></a></p>            
                </div>',
                'poll_type' => 'welcome_mat',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 8,
                'name' => 'plain',
                'form_type' => 'quiz',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <h2 id="headline" style="text-align: center;">QUIZ HEADING GOES HERE</h2>
                        <p id="description" style="font-size: 16px;">Quiz Subheading goes here</p>
                        <button  id="start_button" class="ui fluid primary button">Click to Continue</button>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>
                <div id="success_preview" class="background_image" style="background: white; border-radius: 4px; padding: 10px; width: 70%; margin: auto;" hidden>
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <br/>
                        <h1 id="success_headline" style="text-align: center;">Outcome Heading</h1>
                        <p style="font-size: 20px" id="success_description"></p>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>
                
                <div id="question_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" hidden>
                    <div class="content">
                        <form class="ui form">
                            <h2 style="text-align: center" id="question_header"></h2>
                            <div id="option_preview" class="grouped fields"></div> 
                            <div class="ui fluid buttons">
                                <button class="ui button question_btn" id="prev_btn" type="button">Previous</button>
                                <button class="ui button question_btn" id="next_btn" type="submit">Next</button>
                                <button class="ui button question_btn" id="finish_btn" type="button">Finish</button>
                            </div>                
                        </form>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>
                
                <div id="subscribe_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" hidden>
                    <div class="content">
                        <form id="subscribe_form" class="ui form">
                            <h2 style="text-align: center" id="footnote">Join our mailing list</h2>
                            <div class="field">
                                <label id="email_label">Email</label>
                                <input type="email" name="email" placeholder="please enter your Email" required/>
                            </div>
                            <div id="phone_field" class="field" style="display:none">
                                <label id="phone_label">Phone</label>
                                <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                            </div>
                            <div id="gdpr_field" class="field" style="display:none">
                                <label>GDPR Agreement</label>
                                <div class="ui toggle checkbox">
                                    <input id="gdpr" type="checkbox" name="gdpr">
                                    <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                                </div>
                            </div>
                            <div class="ui fluid buttons">
                                <button type="button" class="ui button" id="skip_btn" type="button">Skip</button>
                                <button type="submit" class="ui button" id="subscribe_btn" type="button">Subscribe</button>
                            </div>                
                        </form>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>',
                'poll_type' => 'popover',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 9,
                'name' => 'plain',
                'form_type' => 'quiz',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" class="ui form background_image">
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <h2 id="headline" style="text-align: center;">QUIZ HEADING GOES HERE</h2>
                        <p id="description" style="font-size: 16px;">Quiz Subheading goes here</p>
                        <button  id="start_button" class="ui fluid primary button">Click to Continue</button>
                    </div>
                </div>
                <div id="success_preview" class="background_image" style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;  " hidden>
                    <br/>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <h1 id="success_headline" style="text-align: center;">Outcome Heading</h1>
                        <p style="font-size: 20px" id="success_description"></p>
                    </div>
                </div>
                <div id="subscribe_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" hidden>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <form id="subscribe_form" class="ui form">
                            <h2 style="text-align: center" id="footnote">Join our mailing list</h2>
                            <div class="field">
                                <label id="email_label">Email</label>
                                <input type="email" name="email" placeholder="please enter your Email" required/>
                            </div>
                            <div id="phone_field" class="field" style="display:none">
                                <label id="phone_label">Phone</label>
                                <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                            </div>
                            <div id="gdpr_field" class="field" style="display:none">
                                <label>GDPR Agreement</label>
                                <div class="ui toggle checkbox">
                                    <input id="gdpr" type="checkbox" name="gdpr">
                                    <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                                </div>
                            </div>
                            <div class="ui fluid buttons">
                                <button type="button" class="ui button" id="skip_btn" type="button">Skip</button>
                                <button type="submit" class="ui button" id="subscribe_btn" type="button">Subscribe</button>
                            </div>                
                        </form>
                    </div>
                </div>
                <div id="question_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" hidden>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <form class="ui form">
                            <h2 style="text-align: center" id="question_header"></h2>
                            <div id="option_preview" class="grouped fields"></div> 
                            <div class="ui fluid buttons">
                                <button class="ui button question_btn" id="prev_btn" type="button">Previous</button>
                                <button class="ui button question_btn" id="next_btn" type="submit">Next</button>
                                <button class="ui button question_btn" id="finish_btn" type="submit">Finish</button>
                            </div>                
                        </form>
                    </div>
                </div>
                <div style="position: absolute; width: 60%; left: 0; right: 0; margin: auto; bottom:20px;">
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    <p style="text-align:center;"><a class="close-popup" href="#"><i class="large angle double down icon"></i></a></p>            
                </div>',
                'poll_type' => 'welcome_mat',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 10,
                'name' => 'plain',
                'form_type' => 'calculator',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <h2 id="headline" style="text-align: center;">CALCULATOR HEADING GOES HERE</h2>
                        <p id="description" style="font-size: 16px;">Calculator Subheading goes here</p>
                        <button  id="start_button" class="ui fluid primary button">Click to Continue</button>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>
                <div id="success_preview" class="background_image" style="text-align:center; background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" hidden>
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <div class="content">
                        <h1 style="text-align: center;">RESULT(S)</h1>
                        <hr/>
                    </div>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>
                
                <div id="question_preview" class="background_image" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" hidden>
                    <div class="content">
                        <form class="ui form">
                            <h2 style="text-align: center" id="question_header"></h2>
                            <div id="option_preview" class="grouped fields"></div> 
                            <div class="ui fluid buttons">
                                <button class="ui button question_btn" id="prev_btn" type="button">Previous</button>
                                <button class="ui button question_btn" id="next_btn" type="submit">Next</button>
                                <button class="ui button question_btn" id="finish_btn" type="button">Finish</button>
                            </div>                
                        </form>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
                </div>
                
                <div id="subscribe_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" hidden>
                    <div class="content">
                        <form id="subscribe_form" class="ui form">
                            <h2 style="text-align: center" id="footnote">Join our mailing list</h2>
                            <div class="field">
                                <label id="email_label">Email</label>
                                <input type="email" name="email" placeholder="please enter your Email" required/>
                            </div>
                            <div id="phone_field" class="field" style="display:none">
                                <label id="phone_label">Phone</label>
                                <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                            </div>
                            <div id="gdpr_field" class="field" style="display:none">
                                <label>GDPR Agreement</label>
                                <div class="ui toggle checkbox">
                                    <input id="gdpr" type="checkbox" name="gdpr">
                                    <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                                </div>
                            </div>
                            <div class="ui fluid buttons">
                                <button type="button" class="ui button" id="skip_btn" type="button">Skip</button>
                                <button type="submit" class="ui button" id="subscribe_btn" type="button">Subscribe</button>
                            </div>                
                        </form>
                        <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    </div>
            </div>',
                'poll_type' => 'popover',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 11,
                'name' => 'plain',
                'form_type' => 'calculator',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" class="ui form background_image">
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <h2 id="headline" style="text-align: center;">CALCULATOR HEADING GOES HERE</h2>
                        <p id="description" style="font-size: 16px;">Calculator Subheading goes here</p>
                        <button  id="start_button" class="ui fluid primary button">Click to Continue</button>
                    </div>
                </div>
                <div id="success_preview" class="background_image" style="text-align:center; background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;  " hidden>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <h1 style="text-align: center;">RESULT(S)</h1>
                    </div>
                </div>
                <div id="subscribe_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" hidden>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <form id="subscribe_form" class="ui form">
                            <h2 style="text-align: center" id="footnote">Join our mailing list</h2>
                            <div class="field">
                                <label id="email_label">Email</label>
                                <input type="email" name="email" placeholder="please enter your Email" required/>
                            </div>
                            <div id="phone_field" class="field" style="display:none">
                                <label id="phone_label">Phone</label>
                                <input name="phone" id="phone" type="text" placeholder="Enter your phone no." />
                            </div>
                            <div id="gdpr_field" class="field" style="display:none">
                                <label>GDPR Agreement</label>
                                <div class="ui toggle checkbox">
                                    <input id="gdpr" type="checkbox" name="gdpr">
                                    <label><a href="#" target="_blank">I agree to the Data storage and Processing Policies</a></label>
                                </div>
                            </div>
                            <div class="ui fluid buttons">
                                <button type="button" class="ui button" id="skip_btn" type="button">Skip</button>
                                <button type="submit" class="ui button" id="subscribe_btn" type="button">Subscribe</button>
                            </div>                
                        </form>
                    </div>
                </div>
                <div id="question_preview" class="background_image"  style="background: white; border-radius: 4px; padding: 25px; width: 100%; height: 100%; margin: auto;" hidden>
                    <div style="width: 60%; height: 60%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <form class="ui form">
                            <h2 style="text-align: center" id="question_header"></h2>
                            <div id="option_preview" class="grouped fields"></div> 
                            <div class="ui fluid buttons">
                                <button class="ui button question_btn" id="prev_btn" type="button">Previous</button>
                                <button class="ui button question_btn" id="next_btn" type="submit">Next</button>
                                <button class="ui button question_btn" id="finish_btn" type="submit">Finish</button>
                            </div>                
                        </form>
                    </div>
                </div>
                <div style="position: absolute; width: 60%; left: 0; right: 0; margin: auto; bottom:20px;">
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                    <p style="text-align:center;"><a class="close-popup" href="#"><i class="large angle double down icon"></i></a></p>            
                </div>',
                'poll_type' => 'welcome_mat',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 12,
                'name' => 'plain',
                'form_type' => 'facebook',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Follow on facebook</h2>
                    <p id="description" style="font-size: 16px;">Follow us on facebook please and please</p>
                    <a href="#"  id="start_action_button" class="ui fluid primary button">Facebook</a>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>',
                'poll_type' => 'popover',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 13,
                'name' => 'plain',
                'form_type' => 'facebook',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Follow on facebook</h2>
                    <p id="description" style="font-size: 16px;">Follow us on facebook please and please</p>
                    <a href="#"  id="start_action_button" class="ui fluid primary button">Facebook</a>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>',
                'poll_type' => 'scrollbox',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 14,
                'name' => 'plain',
                'form_type' => 'twitter',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Follow on Twitter</h2>
                    <p id="description" style="font-size: 16px;">Follow us on Twitter please and please</p>
                    <a href="#"  id="start_action_button" class="ui fluid primary button">Twitter</a>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>',
                'poll_type' => 'popover',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 15,
                'name' => 'plain',
                'form_type' => 'twitter',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Follow on Twitter</h2>
                    <p id="description" style="font-size: 16px;">Follow us on Twitter please and please</p>
                    <a href="#"  id="start_action_button" class="ui fluid primary button">Twitter</a>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>',
                'poll_type' => 'scrollbox',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 16,
                'name' => 'plain',
                'form_type' => 'pinterest',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Follow on pinterest</h2>
                    <p id="description" style="font-size: 16px;">Follow us on pinterest please and please</p>
                    <a  id="start_action_button" class="ui fluid primary button">pinterest</a>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>',
                'poll_type' => 'popover',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 17,
                'name' => 'plain',
                'form_type' => 'pinterest',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Follow on pinterest</h2>
                    <p id="description" style="font-size: 16px;">Follow us on pinterest please and please</p>
                    <a href="#"  id="start_action_button" class="ui fluid primary button">pinterest</a>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>',
                'poll_type' => 'scrollbox',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 18,
                'name' => 'plain',
                'form_type' => 'action',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Create an action</h2>
                    <p id="description" style="font-size: 16px;">Create any action</p>
                    <a href="#"  id="start_action_button" class="ui fluid primary button">Action</a>
                    
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>',
                'poll_type' => 'popover',
            ],
        ]);
        DB::table('themes')->insert([
            [
                'id' => 19,
                'name' => 'plain',
                'form_type' => 'action',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Create an action</h2>
                    <p id="description" style="font-size: 16px;">Create any action</p>
                    <a href="#"  id="start_action_button" class="ui fluid primary button">Action</a>
                    <p style="padding-top: 12px; text-align: center" class="optin_branding" hidden><a href="https://sendmunk.com" target="_blank"><strong>Powered by Sendmunk</strong></a></p>
                </div>',
                'poll_type' => 'scrollbox',
            ],
        ]);

        DB::table('themes')->insert([
            [
                'id' => 20,
                'name' => 'plain',
                'form_type' => 'push_notification',
                'content' => '<div id="form_preview" style="background: white; border-radius: 4px; padding: 25px; width: 70%; margin: auto;" class="ui form background_image">
                    <a href="#" class="close-popup right floated ui icon button"><i class="close icon"></i></a>
                    <h2 id="headline" style="text-align: center;">Push Notification</h2>
                    <p id="description" style="font-size: 16px;">Subscribe here to receive our push notifications</p>
                    <a href="#" id="start_action_button" class="ui fluid primary button">Subscribe</a>
                </div>',
                'poll_type' => 'popover'
            ],
        ]);
    }
}
