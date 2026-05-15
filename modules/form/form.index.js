document.addEventListener('DOMContentLoaded', function() {
  if (document.querySelector('.form form')) {
    import(/* webpackChunkName: "form" */ './assets/script')
      .then(({ default: Form }) => {
        document.querySelectorAll('.form form').forEach(form => {
          new Form(form);
        });
      });
  }
});
