/**
 * Created by BBN on 19/04/2017.
 */
(() => {
  return {
    created(){
  		let ui = this,
      mixins = [{
        props: {
          request: {
            type: String,
            default(){
              return ui.request;
             }
          },
          ui: {
            type: Vue,
            default(){
              return ui;
            }
          }
        },
      }];
    },
    props: ['source'],
    data(){
      return {
        request: ''
      }
    },
    methods: {
      execute(){
        this.post(this.source.root + 'actions/action', {
          request: this.request
        }, (d) => {
          d.load = false;
          let router = this.closest('bbn-router');
          router.add(d);
          router.activateIndex(router.views.length - 1)
        })
      }
    }
  }
})();