define('views/postbox/event',[
    'sandbox',
    'views/postbox/default',
    'views/inputbox/eventtitle',
    'views/inputbox/eventdesc',
    'views/dropdown/event',
    'utils/constants',
    'utils/format',
    'utils/language'
],

// definition
// ----------
function(
    $,
    DefaultView,
    TitleView,
    InputboxView,
    EventView,
    constants,
    format,
    language
) {

    return DefaultView.extend({

        subviews: {
            event: EventView
        },

        template: joms.jst[ 'html/postbox/event' ],

        events: $.extend({}, DefaultView.prototype.events, {
            'click .joms-postbox-event-title': 'onFocus'
        }),

        render: function() {
            DefaultView.prototype.render.apply( this );

            this.$title = this.$('.joms-postbox-title');
            this.$inputbox = this.$('.joms-postbox-inputbox');
            this.$category = this.$('.joms-postbox-event-label-category');
            this.$location = this.$('.joms-postbox-event-label-location');
            this.$date = this.$('.joms-postbox-event-label-date');

            // title
            this.title = new TitleView();
            this.assign( this.$title, this.title );
            this.listenTo( this.title, 'focus', this.onInputFocus );
            this.listenTo( this.title, 'keydown', this.onNewInputUpdate );

            // inputbox
            this.inputbox = new InputboxView({ charcount: true });
            this.assign( this.$inputbox, this.inputbox );
            this.listenTo( this.inputbox, 'focus', this.onInputFocus );
            this.listenTo( this.inputbox, 'keydown', this.onNewInputUpdate );

            return this;
        },

        // ---------------------------------------------------------------------
        // Data validation and retrieval.
        // ---------------------------------------------------------------------

        reset: function() {
            DefaultView.prototype.reset.apply( this );
            this.title && this.title.reset();
            this.inputbox && this.inputbox.reset();
            this.$category && this.onEventSelect({});
        },

        value: function() {
            this.data.text = this.inputbox.value() || '';
            this.data.attachment = {};

            var value;
            for ( var prop in this.subflags )
                if ( value = this.subviews[ prop ].value() )
                    this.data.attachment[ prop ] = value;

            var starttime = (this._data.starttime || '').split(':');
            var endtime = (this._data.endtime || '').split(':');

            $.extend( this.data.attachment, {
                title: this.title.value(),
                catid: this._data.category && this._data.category[0] || null,
                location: this._data.location,
                startdate: this._data.startdate,
                enddate: this._data.enddate,
                allday: false,
                'starttime-hour': starttime[0] || null,
                'starttime-min': starttime[1] || null,
                'endtime-hour': endtime[0] || null,
                'endtime-min': endtime[1] || null
            });

            return DefaultView.prototype.value.apply( this, arguments );
        },

        validate: function() {
            var value = this.value( true ),
                text = value[0];

            if ( !text )
                return 'Event description cannot be empty.';
        },

        // ---------------------------------------------------------------------
        // Inputbox event handlers.
        // ---------------------------------------------------------------------

        onInputFocus: function() {
            this.showMainState();
        },

        onInputUpdate: $.debounce(function() {
            var value = this.value( true ),
                text = value[0],
                attachment = value[1],
                show = true;

            if ( !this.trim( attachment.title ) )
                show = false;
            else if ( !this.trim( text ) )
                show = false;
            else if ( !attachment.catid )
                show = false;
            else if ( !attachment.location )
                show = false;
            else if ( !attachment.startdate )
                show = false;
            else if ( !attachment.enddate )
                show = false;
            else if ( !attachment['starttime-hour'] && !attachment['starttime-min'] )
                show = false;
            else if ( !attachment['endtime-hour'] && !attachment['endtime-min'] )
                show = false;

            this.$save[ show ? 'show' : 'hide' ]();

        }, 300 ),

        onNewInputUpdate: $.debounce(function() {
            var value = this.value( true ),
                text = value[0],
                attachment = value[1],
                show = true;

            if ( !this.trim( attachment.title ) )
                show = false;
            else if ( !this.trim( text ) )
                show = false;
            else if ( !attachment.catid )
                show = false;
            else if ( !attachment.location )
                show = false;
            else if ( !attachment.startdate )
                show = false;
            else if ( !attachment.enddate )
                show = false;
            else if ( !attachment['starttime-hour'] && !attachment['starttime-min'] )
                show = false;
            else if ( !attachment['endtime-hour'] && !attachment['endtime-min'] )
                show = false;

            this.$save[ show ? 'show' : 'hide' ]();

        }, 300 ),

        onPost: function() {
            var conf = constants.get('conf') || {},
                limit = +conf.limitevent,
                created = +conf.createdevent,
                message;

            if ( created >= limit ) {
                message = language.get('event.create_limit_exceeded') || 'You have reached the event creation limit.';
                message = message.replace( '%1$s', limit );
                window.alert( message );
                return;
            }

            DefaultView.prototype.onPost.apply( this, arguments );
        },

        onPostSuccess: function() {
            DefaultView.prototype.onPostSuccess.apply( this, arguments );
            var conf = constants.get('conf') || {};
            conf.createdevent = +conf.createdevent + 1;
        },

        // ---------------------------------------------------------------------
        // Dropdowns event handlers.
        // ---------------------------------------------------------------------

        onEventSelect: function( data ) {
            if ( !data.category ) {
                this.$category.hide();
            } else {
                this.$category.find('.joms-input-text').html( data.category && data.category[1] );
                this.$category.show();
            }

            if ( !data.location ) {
                this.$location.hide();
            } else {
                this.$location.find('.joms-input-text').html( data.location );
                this.$location.show();
            }

            var str = [];
            if ( !data.startdate || !data.enddate ) {
                this.$date.hide();
            } else {
                str.push( data.startdate[1] + ' ' + data.starttime[1] );
                str.push( data.enddate[1] + ' ' + data.endtime[1] );
                this.$date.find('.joms-input-text').html( str.join(' &mdash; ') );
                this.$date.show();
            }

            data.startdate && (data.startdate = data.startdate[0]);
            data.starttime && (data.starttime = data.starttime[0]);
            data.enddate && (data.enddate = data.enddate[0]);
            data.endtime && (data.endtime = data.endtime[0]);

            this._data = data;
            this.onInputUpdate();
        },

        onPrivacySelect: function( data ) {
            var icon = this.$tabprivacy.find('use'),
                href = icon.attr('xlink:href');

            href = href.replace(/#.+$/, '#joms-icon-' + data.icon );

            this.$tabprivacy.find('use').attr( 'xlink:href', href );
            this.$tabprivacy.find('span').html( data.label );
        },

        // ---------------------------------------------------------------------
        // Ajax response parser.
        // ---------------------------------------------------------------------

        parseResponse: function( response ) {
            var elid = 'activity-stream-container',
                data, temp;

            if ( response && response.length ) {
                for ( var i = 0; i < response.length; i++ ) {
                    if ( response[i][1] === elid ) {
                        data = response[i][3];
                    }
                    if ( response[i][0] === 'al' ) {
                        temp = response[i][3];
                        window.alert( $.isArray( temp ) ? temp.join('. ') : temp );
                    }
                    if ( response[i][1] === '__throwError' ) {
                        temp = response[i][3];
                        window.alert( $.isArray( temp ) ? temp.join('. ') : temp );
                    }
                    if ( response[i][0] === 'cs' ) {
                        try {
                            eval( response[i][1] );
                        } catch (e) {}
                    }
                }
            }

            return data;
        },

        // ---------------------------------------------------------------------
        // Helper functions.
        // ---------------------------------------------------------------------

        getTemplate: function() {
            var lang = language.get('event') || {};
            if ( lang.event_detail )
                lang.event_detail = lang.event_detail.toLowerCase();

            var html = this.template({
                juri: constants.get('juri'),
                language: {
                    postbox: language.get('postbox') || {},
                    event: lang
                }
            });

            return $( html ).hide();
        },

        getStaticAttachment: function() {
            if ( this.staticAttachment )
                return this.staticAttachment;

            this.staticAttachment = $.extend({},
                constants.get('postbox.attachment') || {},
                { type: 'event' }
            );

            return this.staticAttachment;
        },

        trim: function( text ) {
            return (text || '').replace( /^\s+|\s+$/g, '' );
        }

    });

});