(function(){
  
  var chat = {
    messageToSend: '',
    messageResponses: [
      'Why did the web developer leave the restaurant? Because of the table layout.',
      'How do you comfort a JavaScript bug? You console it.',
      'An SQL query enters a bar, approaches two tables and asks: "May I join you?"',
      'What is the most used language in programming? Profanity.',
      'What is the object-oriented way to become wealthy? Inheritance.',
      'An SEO expert walks into a bar, bars, pub, tavern, public house, Irish pub, drinks, beer, alcohol'
    ],
    init: function() {
      this.$socket = new WebSocket("ws://127.0.0.1:8004/");
      this.cacheDOM();
      this.bindEvents();
      this.render();
    },
    cacheDOM: function() {
      this.$chatHistory = $('.chatbox .chat-history');
      this.$button = $('.chatbox button');
      this.$chats = $('.chatbox .people-list ul.list div.list-view li');
      this.$rollUp = $('.chatbox .chat-rollUp');
      this.$textarea = $('.chatbox #message-to-send');
      this.$chatHistoryList =  this.$chatHistory.find('ul');
    },
    bindEvents: function() {
      if(this.$chats['0'] !== undefined){
        this.$chats['0'].setAttribute('class', this.$chats['0'].getAttribute('class') + ' active');
      }
      this.$rollUp.on('click', this.rollUp.bind(this));
      this.$chats.on('click', this.choseChat.bind(this));
      this.bindChatEvents();
    },
    bindChatEvents: function(){
      this.$socket.onmessage = function(evt) {
        var templateResponse = Handlebars.compile( $(".chatbox #message-response-template").html()),
            data = JSON.parse(evt.data),
            contextResponse = {
              response: data.message,
              time: chat.getCurrentTime(),
              author: data.author
            };

        chat.$chatHistoryList.append(templateResponse(contextResponse));
        chat.scrollToBottom();
      };
      this.$button.on('click', this.addMessage.bind(this));
      this.$textarea.on('keyup', this.addMessageEnter.bind(this));
    },
    choseChat: function(e){
      if(e.currentTarget.getAttribute('class').match(/active/)){
        return false;
      }
      $('.chatbox li.active').removeClass('active');
      e.currentTarget.setAttribute('class', e.currentTarget.getAttribute('class') + ' active');
      var chatWindow = document.querySelector(".chatbox .container");
      if(chatWindow.querySelector(".chat") === undefined){
        var item = document.createElement('div');
        item.setAttribute('class', 'chat');
        chatWindow.appendChild(item);
      }
      $.ajax({
        url: '/loadchat',
        type: 'POST',
        data: {
          'chatID': e.currentTarget.querySelector(".chatID").value
        },
        success: function(data){
          if(data != false){
            $('.chatbox .container .chat').replaceWith(data);
          }else{
            e.currentTarget.remove();
          }
        }
      });
      this.bindChatEvents();
    },
    rollUp: function(e){
      e.currentTarget.parentNode.parentNode.style.width = e.currentTarget.parentNode.parentNode.style.width == '86px' ? '240px' : '86px';
      e.currentTarget.querySelector("span").innerHTML = e.currentTarget.parentNode.parentNode.style.width == '86px' ? '<i class="fa fa-arrow-left"></i>' : '<i class="fa fa-arrow-right"></i>';
    },
    render: function() {
      this.scrollToBottom();
      if (this.messageToSend.trim() !== '') {
        var template = Handlebars.compile( $(".chatbox #message-template").html());
        var context = { 
          messageOutput: this.messageToSend,
          myName: document.querySelector(".afterMenu .dropdown .btn").innerHTML,
          chatID: document.querySelector(".chatbox .people-list .list li.active").getAttribute('data-key'),
          myID: document.querySelector(".afterMenu .dropdown .btn").getAttribute("data-userid"),
          time: this.getCurrentTime()
        };

        this.$socket.send(JSON.stringify(context));

        this.$chatHistoryList.append(template(context));
        this.scrollToBottom();
        this.$textarea.val('');
      }
    },
    addMessage: function() {
      this.messageToSend = this.$textarea.val();
      this.render();         
    },
    addMessageEnter: function(event) {
        // enter was pressed
        if (event.keyCode === 13) {
          this.addMessage();
        }
    },
    scrollToBottom: function() {
        if(this.$chatHistory[0] !== undefined) {
          this.$chatHistory.scrollTop(this.$chatHistory[0].scrollHeight);
        }
    },
    getCurrentTime: function() {
      return new Date().toLocaleTimeString().
              replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
    }
  };
  
  chat.init();
})();