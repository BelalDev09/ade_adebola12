@extends('backend.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">SMTP Settings</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Configure Email Settings</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.smtp.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="mail_mailer" class="form-label">Mail Mailer</label>
                                <select class="form-select @error('mail_mailer') is-invalid @enderror" id="mail_mailer"
                                    name="mail_mailer" required>
                                    <option value="">-- Select Mailer --</option>
                                    <option value="smtp" {{ $smtp->mail_mailer == 'smtp' ? 'selected' : '' }}>SMTP
                                    </option>
                                    <option value="sendmail" {{ $smtp->mail_mailer == 'sendmail' ? 'selected' : '' }}>
                                        Sendmail</option>
                                    <option value="mailgun" {{ $smtp->mail_mailer == 'mailgun' ? 'selected' : '' }}>Mailgun
                                    </option>
                                    <option value="ses" {{ $smtp->mail_mailer == 'ses' ? 'selected' : '' }}>SES</option>
                                    <option value="postmark" {{ $smtp->mail_mailer == 'postmark' ? 'selected' : '' }}>
                                        Postmark</option>
                                </select>
                                @error('mail_mailer')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mail_host" class="form-label">Mail Host</label>
                                <input type="text" class="form-control @error('mail_host') is-invalid @enderror"
                                    id="mail_host" name="mail_host" value="{{ $smtp->mail_host }}" >
                                @error('mail_host')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mail_port" class="form-label">Mail Port</label>
                                <select class="form-select @error('mail_port') is-invalid @enderror" id="mail_port"
                                    name="mail_port" required>
                                    <option value="">-- Select Port --</option>
                                    <option value="25" {{ $smtp->mail_port == '25' ? 'selected' : '' }}>25</option>
                                    <option value="465" {{ $smtp->mail_port == '465' ? 'selected' : '' }}>465</option>
                                    <option value="587" {{ $smtp->mail_port == '587' ? 'selected' : '' }}>587</option>
                                    <option value="2525" {{ $smtp->mail_port == '2525' ? 'selected' : '' }}>2525</option>
                                </select>
                                @error('mail_port')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mail_username" class="form-label">Mail Username</label>
                                <input type="text" class="form-control @error('mail_username') is-invalid @enderror"
                                    id="mail_username" name="mail_username" value="{{ $smtp->mail_username }}">
                                @error('mail_username')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mail_password" class="form-label">Mail Password</label>
                                <input type="password" class="form-control @error('mail_password') is-invalid @enderror"
                                    id="mail_password" name="mail_password"
                                    placeholder="Leave empty to keep current password">
                                @error('mail_password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mail_encryption" class="form-label">Encryption</label>
                                <select class="form-select @error('mail_encryption') is-invalid @enderror"
                                    id="mail_encryption" name="mail_encryption">
                                    <option value="">-- Select Encryption --</option>
                                    <option value="tls" {{ $smtp->mail_encryption == 'tls' ? 'selected' : '' }}>TLS
                                    </option>
                                    <option value="ssl" {{ $smtp->mail_encryption == 'ssl' ? 'selected' : '' }}>SSL
                                    </option>
                                    <option value="null" {{ $smtp->mail_encryption == 'null' ? 'selected' : '' }}>None
                                    </option>
                                </select>
                                @error('mail_encryption')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mail_from_address" class="form-label">From Email Address</label>
                                <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror"
                                    id="mail_from_address" name="mail_from_address" value="{{ $smtp->mail_from_address }}">
                                @error('mail_from_address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mail_from_name" class="form-label">From Name</label>
                                <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror"
                                    id="mail_from_name" name="mail_from_name" value="{{ $smtp->mail_from_name }}">
                                @error('mail_from_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Save SMTP Settings</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
