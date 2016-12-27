// lazyload config

angular.module('myApp')
  
  .config(['$ocLazyLoadProvider', function($ocLazyLoadProvider) {
      
      $ocLazyLoadProvider.config({
          debug:  true,
          events: true,
          modules: [
              {
                  name: 'LocalStorage',
                  files: [
                      'node_modules/angular-local-storage/dist/angular-local-storage.min.js'
                  ]
              }
          ]
      });
  }])
;
