<div class="app font-sans min-w-screen min-h-screen bg-grey-lighter py-8 px-4">
    <div class="mail__wrapper max-w-md mx-auto">
        <div class="mail__content bg-white p-8 shadow-md">
            <p class="content__body py-8 border-b">
            <p>
            <h5>Hey {{$data->username}},</h5><br><br>So great! You have successfully registered an account. And your
            account information:
            </p>

            <p>Username: {{$data->username}}</p>

            <p>Email: {{$data->email}}</p>

            <p>Password: {{$data->password}}</p>

            <p>Keys: {{$data->secret_access_key}}</p>


            <p class="text-sm">
            <h5>Best regards!<br> Cloud</h5>
            </p>
        </div>

        <div class="content__footer mt-8 text-center text-grey-darker">
            <h3 class="text-base sm:text-lg mb-4">Thanks for using The App!</h3>

            <p>www.cloud-image.com</p>
        </div>

    </div>

    <div class="mail__meta text-center text-sm text-grey-darker mt-8">
        <div class="meta__social flex justify-center my-4">
            <a href="#"
               class="flex items-center justify-center mr-4 bg-black text-white rounded-full w-8 h-8 no-underline"><i
                    class="fab fa-facebook-f"></i></a>
            <a href="#"
               class="flex items-center justify-center mr-4 bg-black text-white rounded-full w-8 h-8 no-underline"><i
                    class="fab fa-instagram"></i></a>
            <a href="#"
               class="flex items-center justify-center bg-black text-white rounded-full w-8 h-8 no-underline">
                <i class="fab fa-twitter"></i>
            </a>
        </div>
    </div>

</div>

</div>
</div>

</div>
