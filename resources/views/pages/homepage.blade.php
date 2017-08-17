@extends('layouts.app')

@section('contents')

<section class="section">
    <div class="container has-text-centered">
        <h1 class="title is-1">apress</h1>
        <h3 class="title is-3">A simple Twitter scheduler.</h3>
    </div>
</section>

<section class="section">
    <div class="container has-text-centered">
        <a href="{{ url('login') }}" class="button is-large">Login with Twitter</a>
    </div>
</section>

<section class="section">
    <div class="container has-text-centered">
        <p>
            <strong>Sporting:</strong>
        </p>
        <ul>
            <li>unlimited accounts</li>
            <li>unlimited tweets</li>
            <li>choose date and time</li>
            <li>... stop. Well, I said it is simple...</li>
        </ul>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="content has-text-centered">
            <p>
                <strong>apress</strong> by <a href="http://madbob.org/">Roberto Guido</a>. The source code is licensed <a href="https://www.gnu.org/licenses/agpl-3.0.txt">AGPLv3+</a>.
            </p>
            <p>
                <a class="icon" href="https://github.com/madbob/apress">
                    <i class="fa fa-github"></i>
                </a>
            </p>
        </div>
    </div>
</footer>

@endsection
