var PrologueClient = {

  queryToServer: function(path, data, callback) {

    send(path, data, callback);

    function send(path, data, callback) {

      function getParams(data) {
        return typeof data === 'string' ? data : Object.keys(data).map(
          function(k) {
            return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
          }
        ).join('&');
      }

      var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

      if(path === 'prepareToRender') {
        xhr.open('POST', '/PSSR/prepareToRender.php');
      }
      else {
        xhr.open('POST', PrologueClient.getApiUrl(path), true);
      }

      xhr.onreadystatechange = function() {

        if(xhr.readyState > 3) {
          if(xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          }
          else {
            netWorkError();
            setTimeout(function() {
              send(path, data, callback);
            }, 7000);
          }
        }
      };


      if(!data.form) {
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(getParams(data));
      }
      else {
        xhr.send(data.form);
      }

    }

    function netWorkError() {
      document.body.innerHTML += '<div id="PrologueClientNetWorkError" style="text-align: center; font-size: 16px; padding: 10px; background: #555; z-index: 9999999; bottom: 0; left: 0; right: 0; color: #fff; position: fixed; width: 100%;">Network connection error!<br>Reconnect...</div>';
      setTimeout(function() {
        document.getElementById('PrologueClientNetWorkError').remove();
      }, 7000);
    }

  },


  getApiUrl: function(path) {

    path = path.replace(':', '/?action=');

    var url = this.getServer() + '/api/' + path;

    return url;
  },

  getServer: function() {

    var config = PrologueClient.getConfig();

    if(config.mode === 'demo') {
      return config.server.demo;
    }
    else if(config.mode === 'work') {
      return config.server.work;
    }

  },

  getConfig: function() {
    var configSelector = document.getElementById('PrologueClient');
    return {
      'server': {
        'demo': configSelector.getAttribute('data-demo-server'),
        'work': configSelector.getAttribute('data-work-server')
      },
      'mode': configSelector.getAttribute('data-mode')
    }
  },
  prepareToRender: function prepareToRender(code) {

    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(function() {

        var url         = window.location.pathname;
        var title       = document.title;
        var description = document.querySelector('meta[name="description"]').getAttribute('content');
        var html        = document.getElementById('app').innerHTML;

        PrologueClient.queryToServer('prepareToRender', {
          "url": url,
          "title": title,
          "description": description,
          "html": html,
          "code": code
        }, function() {

        });
      }, 300);
    });

    document.getElementById('js_PSSR').remove();

  }

};

