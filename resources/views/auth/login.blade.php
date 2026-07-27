@extends('layouts.app')
<style>

.shell.member{
    width:100%;
    max-width:500px;
    margin:48px auto;
    padding:0 15px;
}

.shell-kicker{
    margin-bottom:12px;
    font-size:13px;
    letter-spacing:1px;
    color:#888;
}

.panel{
    width:100%;
    background:#fff;
    border:1px solid #ddd;
    border-radius:8px;
    overflow:hidden;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}

.topbar{
    display:flex;
    align-items:center;
    justify-content:flex-start;
    padding:16px 20px;
    border-bottom:1px solid #eee;
    background:#fafafa;
}

.brand{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
    font-weight:600;
    font-size:18px;
}

.brand-mark{
    font-size:22px;
    font-weight:bold;
}

.panel-body{
    display:flex;
    justify-content:center;
    padding:24px;
}

.block{
    width:100%;
    max-width:420px;
}

.stack{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.stack label{
    display:block;
    font-weight:500;
    font-size:14px;
}

.stack input{
    width:100%;
    margin-top:6px;
    padding:12px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:15px;
    box-sizing:border-box;
}

.stack input:focus{
    outline:none;
    border-color:#0d6efd;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:6px;
    background:#0d6efd;
    color:#fff;
    font-size:15px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#0b5ed7;
}

.error-text{
    display:block;
    margin-top:5px;
    color:#dc3545;
    font-size:13px;
}

.meta{
    margin-top:15px;
    font-size:13px;
    color:#666;
    word-break:break-word;
}

@media (max-width:768px){

    .shell.member{
        max-width:450px;
        margin:30px auto;
    }

    .panel-body{
        padding:20px;
    }

    .brand{
        font-size:16px;
    }

    .brand-mark{
        font-size:20px;
    }

}

@media (max-width:576px){

    .shell.member{
        max-width:100%;
        padding:0 12px;
        margin:20px auto;
    }

    .topbar{
        padding:14px;
    }

    .panel-body{
        padding:16px;
    }

    .brand{
        justify-content:center;
        text-align:center;
        width:100%;
        font-size:15px;
    }

    .brand-mark{
        font-size:18px;
    }

    .shell-kicker{
        text-align:center;
    }

    .stack input,
    button{
        font-size:16px;
    }

}
</style>
@section('content')
<div class="shell member login-shell">
    <p class="shell-kicker">LOGIN SCREEN</p>

    <div class="panel">
        <div class="topbar">
            <div class="brand">
                <span class="brand-mark">&gt;URL&lt;</span>
                <span>Sembark URL Shortner</span>
            </div>
        </div>

        <div class="panel-body">
            <div class="block login-block">

                <form method="POST" action="{{ route('login.store') }}" class="stack">
                    @csrf

                    <label>
                        Email
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="e.g. sample@example.com"
                            required
                        >
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </label>

                    <label>
                        Password
                        <input
                            type="password"
                            name="password"
                            placeholder="**********"
                            required
                        >
                    </label>

                    <div>
                        <button type="submit">Login</button>
                    </div>

                </form>

                
            </div>
        </div>
    </div>
</div>
@endsection