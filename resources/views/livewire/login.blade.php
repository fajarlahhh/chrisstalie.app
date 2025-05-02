<div>
    <!-- BEGIN login -->
    <div class="login login-v1">
        <!-- BEGIN login-container -->
        <div class="login-container">
            <!-- BEGIN login-header -->
            <div class="login-header">
                <div class="brand">
                    <div class="d-flex align-items-center">
                        <img src="/assets/img/login.png" class="h-70px" alt="" />
                    </div>
                </div>
                <div class="icon">
                    <i class="fa fa-lock"></i>
                </div>
            </div>
            <!-- END login-header -->

            <!-- BEGIN login-body -->
            <div class="login-body">
                <!-- BEGIN login-content -->
                <div class="login-content fs-13px">
                    <form wire:submit="login">
                        <div class="form-floating mb-20px">
                            <input type="email" class="form-control fs-13px h-45px" id="email" autocomplete="off"
                                wire:model="email" placeholder="Email Address" required />
                            <label for="email" class="d-flex align-items-center">Email</label>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-floating mb-20px">
                            <input type="password" class="form-control fs-13px h-45px" id="password" autocomplete="off"
                                wire:model="password" placeholder="Password" required />
                            <label for="password" class="d-flex align-items-center">Password</label>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-check mb-20px">
                            <input class="form-check-input" type="checkbox" id="rememberMe" wire:model="remember" />
                            <label class="form-check-label" for="rememberMe">
                                Remember Me
                            </label>
                        </div>
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-theme h-45px d-block w-100 btn-lg">Sign me in</button>
                        </div>
                    </form>
                    <x-alert />
                </div>
                <!-- END login-content -->
            </div>
            <!-- END login-body -->
        </div>
        <!-- END login-container -->
    </div>
    <!-- END login -->
</div>
