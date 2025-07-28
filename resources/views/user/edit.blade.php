<x-app-layout title="Edit User - Flopac.id" icon='<i data-lucide="user-edit" class="me-3"></i> Edit User'>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Edit User</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Edit data user: {{ $user->name }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm form-card">
            <div class="card-body">
                <form id="userEditForm" action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control minimal-input @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="Nama Lengkap"
                                       required>
                                <label for="name">Nama Lengkap *</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control minimal-input @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username', $user->username) }}"
                                       placeholder="Username"
                                       required>
                                <label for="username">Username *</label>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" 
                                       class="form-control minimal-input @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       placeholder="Email"
                                       required>
                                <label for="email">Email *</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <div class="minimal-select-container">
                                <label for="role" class="minimal-label">Role *</label>
                                <select class="form-select minimal-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $roleItem)
                                        @if($roleItem->name == 'Owner' && $ownerExists && $user->roles->first()?->name != 'Owner')
                                            <!-- Skip Owner role if already exists and current user is not owner -->
                                        @else
                                            <option value="{{ $roleItem->name }}" 
                                                    {{ (old('role') ?: $user->roles->first()?->name) == $roleItem->name ? 'selected' : '' }}>
                                                {{ $roleItem->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" 
                                       class="form-control minimal-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Password Baru">
                                <label for="password">Password Baru (opsional)</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted mt-2">Kosongkan jika tidak ingin mengubah password</small>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" 
                                       class="form-control minimal-input" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       placeholder="Konfirmasi Password Baru">
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <a href="{{ route('user.index') }}" class="btn minimal-btn-secondary">
                            <i data-lucide="x"></i> Batal
                        </a>
                        <button type="submit" id="submitBtn" class="btn minimal-btn-primary">
                            <i data-lucide="save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-form-styles />
</x-app-layout>

@push('scripts')
<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Select2 Integration with Minimal Styling */
    .select2-container--default .select2-selection--single {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        height: 56px;
        padding: 1rem;
        background: #ffffff;
        transition: all 0.3s ease;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px;
        color: #495057;
        font-size: 14px;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #4AC8EA;
        box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
    }

    .select2-dropdown {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .select2-container--default .select2-results__option--highlighted {
        background-color: #4AC8EA;
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize Select2 for role selection
        $('#role').select2({
            placeholder: 'Pilih Role',
            allowClear: false,
            width: '100%'
        });

        // Setup AJAX form handler
        setupAjaxForm('#userEditForm', {
            loadingTitle: 'Mengupdate User...',
            loadingText: 'Sedang memproses perubahan data user',
            successTitle: 'User Berhasil Diupdate!',
            successText: 'Data user telah diperbarui',
            redirectUrl: '{{ route("user.index") }}'
        });

        // Auto-hide alert after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>
@endpush
