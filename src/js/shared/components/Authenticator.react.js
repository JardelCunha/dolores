"use strict";

var $ = require("jquery");
var React = require("react");

var API = require("../api");

var SignupForm = require("./SignupForm.react");
var ProfileForm = require("./ProfileForm.react");

var defaultMessage = "Conecte-se e dê suas ideias para a cidade:";

var Authenticator = React.createClass({
  getInitialState: function() {
    return {
      auth: {},
      data: {},
      share: false,
      show: false,
      signup: false,
      profile: false,
      waiting: false
    };
  },

  defaultRefreshCallback: function(from) {
    if (from === "signup") {
      this.setState({
        share: true,
        show: true
      });
    } else {
      location.reload();
    }
  },

  componentDidUpdate: function(prevProps, prevState) {
    if (!prevState.share && this.state.share) {
      // Generate social buttons in success message
      window.FB.XFBML.parse();
      window.twttr.widgets.load();
    }
  },

  componentWillMount: function() {
    window.DoloresAuthenticator = {
      signIn: function(message, refreshCallback) {
        var newState = {
          show: true
        };

        if (message != null) {
          newState.message = message;
        } else {
          newState.message = defaultMessage;
        }

        if (refreshCallback != null) {
          newState.refreshCallback = refreshCallback;
        } else {
          newState.refreshCallback = this.defaultRefreshCallback;
        }

        if ($("body").hasClass("logged-in")) {
          newState.refreshCallback();
        } else {
          this.setState(newState);
        }
      }.bind(this),

      editUserInfo: function() {
        window.DoloresAuthenticator.signIn(null, function() {
          this.setState({
            profile: true,
            show: true,
            waiting: true
          });
          API.route("userinfo").get().done(function(response) {
            this.setState({
              profileData: response.data,
              refreshCallback: this.defaultRefreshCallback,
              waiting: false
            });
          }.bind(this)).fail(function(response) {
            console.log(response);
            if ("error" in response.responseJSON) {
              alert("Erro: " + response.responseJSON.error);
            }
            this.setState({
              profile: false,
              show: false,
              waiting: false
            });
          }.bind(this));
        }.bind(this));
      }.bind(this),

      setAuth: function(auth) {
        this.setState({
          auth: auth,
          waiting: true
        });

        API.route("signin").post(auth).done(function(response) {
          if (response.action === "refresh") {
            this.refresh();
          } else if (response.action === "signup") {
            var data = response.data;
            data.auth = auth;
            this.setState({
              signup: true,
              data: data,
              waiting: false
            });
          }
        }.bind(this)).fail(function(data) {
          alert("Erro na autenticação: " + data.responseJSON.error);
          location.reload();
          this.setState({
            waiting: false
          });
        });
      }.bind(this),

      hasAuth: function(type) {
        return this.hasAuth() && this.state.auth.type === type;
      }.bind(this)
    };
  },

  refresh: function(from) {
    $("body").addClass("logged-in");

    API.route("userheader").get().done(function(response) {
      $(".user-signin").replaceWith(response.html);
    }).fail(function(response) {
      console.log("Error getting user header", response);
    });

    this.setState(this.getInitialState());
    this.state.refreshCallback(from);
  },

  hasAuth: function() {
    return "type" in this.state.auth;
  },

  signinWithFacebook: function() {
    window.fbLogin();
  },

  signinWithGoogle: function() {
    window.googleLogin();
  },

  hide: function() {
    this.setState(this.getInitialState());
  },

  containerClick: function(e) {
    if (e.target.className === "lightbox-cell") {
      this.hide();
    }
  },

  render: function() {
    if (!this.state.show) {
      return null;
    }

    var lightboxContent = null;
    if (this.state.waiting) {
      var spinner = "fa fa-refresh fa-spin fa-4x";
      lightboxContent = (
        <div className="lightbox-content">
          <p style={{textAlign: "center"}}><i className={spinner}></i></p>
          <p style={{textAlign: "center"}}>Carregando...</p>
        </div>
      );
    } else if (this.state.signup) {
      lightboxContent = (
        <div className="lightbox-content">
          <SignupForm
              data={this.state.data}
              refreshCallback={this.refresh}
              >
          </SignupForm>
        </div>
      );
    } else if (this.state.share) {
      var shareUrl = "http://" + location.host + "/participe/";
      lightboxContent = (
        <div className="lightbox-content">
          <div className="signup-logo"></div>
          <h3 className="signup-social-header">
            Agora espalhe a ideia para que outros amigos e amigas participem!
          </h3>
          <div className="signup-social-buttons social-media">
            <div className="fb-share-button"
                data-href={shareUrl}
                data-layout="button_count"></div>
            <a href="https://twitter.com/share"
                className="twitter-share-button"
                data-url={shareUrl}
                data-lang="pt"></a>
          </div>
        </div>
      );
    } else if (this.state.profile) {
      lightboxContent = (
        <div className="lightbox-content">
          <ProfileForm
              data={this.state.profileData}
              refreshCallback={this.refresh}
              >
          </ProfileForm>
        </div>
      );
    } else {
      lightboxContent = (
        <div className="lightbox-content">
          <p className="signin-text">{this.state.message}</p>
          <button
              className="signin-button signin-facebook"
              onClick={this.signinWithFacebook}
              >
            <i className="fa fa-2x fa-fw fa-facebook"></i>
            Conectar com Facebook
          </button>
          <button
              className="signin-button signin-google"
              onClick={this.signinWithGoogle}
              >
            <i className="fa fa-2x fa-fw fa-google"></i>
            Conectar com Google
          </button>
        </div>
      );
    }

    return (
      <div className="lightbox-overlay">
        <div className="lightbox-table">
          <div className="lightbox-cell" onClick={this.containerClick}>
            <div className="lightbox">
              <button className="lightbox-close" onClick={this.hide}>X</button>
              {lightboxContent}
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Authenticator;
