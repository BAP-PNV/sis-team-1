<div class="app font-sans min-w-screen min-h-screen bg-grey-lighter py-8 px-4">
    <div class="mail__wrapper max-w-md mx-auto">
        <div class="mail__content bg-white p-8 shadow-md">
            <p class="content__body py-8 border-b">
            <p>
            <h5>Hey {{ $data->username }},</h5><br><br>So great! You have successfully registered an account. And your
            account information:
            </p>

            <p>Username: {{ $data->username }}</p>

            <p>Email {{ $data->email }}</p>

            <p>Password: {{ $data->password }}</p>

            <p>Keys: {{ $data->secret_access_key }}</p>

            <p>API endpoint: {{config('constants.APP_URL')}}/api/</p>

            <p class="text-sm">
            <h5>Best regards!<br> Cloud</h5>
            </p>
        </div>

        <div class="content__footer mt-8 text-center text-grey-darker">
            <h3 class="text-base sm:text-lg mb-4">Thanks for using The App!</h3>

            <p>www.cloud-image.com</p>
        </div>

    </div>
</div>
