@extends('layouts.app')

@section('contents')

@if ($errors->any())
    <div class="notification is-danger floating-notification">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@else
    @if(Session::has('feedback'))
        <div class="notification is-primary floating-notification">
            {{ Session::get('feedback') }}
        </div>
    @endif
@endif

<section class="section">
    <div class="container">
        <form method="POST" action="{{ url('dashboard/save') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="tweet" value="{{ $edit ? $edit->id : '' }}">

            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">
                                    @if($edit == null)
                                        Schedule new Tweet
                                    @else
                                        Edit Tweet
                                    @endif
                                </label>
                                <div class="control">
                                    <textarea class="textarea" name="content">{{ $edit ? $edit->content : '' }}</textarea>
                                    <div id="charnum"><span>{{ $edit ? 280 - strlen($edit->content) : 280 }}</span> / 280</div>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            @if($edit)
                                @foreach($edit->media as $media)
                                    <div class="media-preview">
                                        <input type="hidden" name="keep_media[]" value="{{ $media->id }}">
                                        <img class="preview" src="{{ $media->as_data }}">
                                        <a class="button is-danger is-small">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @endif

                            <div class="media-preview is-invisible">
                                <input class="file-input" type="file" name="media[]">
                                <img class="preview" src="">
                                <a class="button is-danger is-small">
                                    <i class="fa fa-remove"></i>
                                </a>
                            </div>

                            <div class="file">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="media[]">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fa fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Attach Image
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="is-divider" data-content="OR"></div>

            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <div class="field">
                        <label class="label">
                            @if($edit == null)
                                Schedule new Retweet
                            @else
                                Edit a Retweet
                            @endif
                        </label>
                        <div class="control">
                            <input class="input" name="retweet" value="{{ $edit ? $edit->retweet : '' }}" placeholder="https://twitter.com/handle/status/XXX">
                        </div>
                    </div>
                </div>
            </div>

            <div class="is-divider" data-content="THEN"></div>

            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <div class="control">
                                    <input id="datetimepicker" type="text" name="schedule" value="{{ $edit ? $edit->schedule : date('Y-m-d H:i:s') }}">
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="field">
                                <div class="control">
                                    @foreach($user->accounts as $index => $account)
                                        <label class="label account-select">
                                            <input type="radio" name="account" value="{{ $account->id }}" {{ $edit != null && $edit->account_id == $account->id ? 'checked' : ($index == 0 ? 'checked' : '') }}>
                                            <img src="{{ $account->picture_url }}" alt="{{ $account->handle }}"> {{ $account->handle }}
                                            <a href="{{ url('dashboard/account/remove/' . $account->id) }}" class="button is-danger is-pulled-right is-small">Remove Account</a>
                                        </label>
                                    @endforeach

                                    <br/>
                                    <br/>
                                    <label class="label">
                                        <a href="{{ url('login') }}">Add Another Account</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="is-divider" data-content="FINALLY"></div>

            <div class="field is-grouped is-grouped-centered">
                <p class="control">
                    <button class="button is-primary is-large">Submit</button>
                </p>
                @if($edit)
                    <p class="control">
                        <a href="{{ url('dashboard') }}" class="button">Cancel</a>
                    </p>
                @endif
            </div>
        </form>
    </div>
</section>

@if($user->errorTweets()->count() != 0)
    <section class="section">
        <div class="container">
             @foreach($user->errorTweets as $tweet)
                 @include('tweet.box', ['tweet' => $tweet, 'error' => true])
             @endforeach
        </div>
    </section>
@endif

<section class="section">
    <div class="container">
        @if($user->tweets()->count() == 0)
            <div class="notification is-primary">
                There are no scheduled messages.
            </div>
        @else
             @foreach($user->tweets as $tweet)
                 @include('tweet.box', ['tweet' => $tweet, 'error' => false])
             @endforeach
        @endif
    </div>
</section>

@endsection
