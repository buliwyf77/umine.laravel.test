<p>Backend de prueba tecnica para UMINE.</p>

<p>Para correr correctamente crear una app Laravel (debe crear virtualhost http://umine.laravel.test)</p>



<p><b>Nota: para que corra correctamente debe editar el archivo app/Providers/AppServiceProvider.php y sustituir la funcion boot con la siguiente:</b></p>
<hr>
<code>

 public function boot()
    {
        //
        Schema::defaultStringLength(191);
    }
}

</code>