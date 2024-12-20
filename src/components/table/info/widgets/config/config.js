// Javascript Document
(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-database'] + '/'
      }
    },
    computed: {
      size() {
        return this.source.size ? bbn.fn.formatBytes(this.source.size) : '0b';
      }
    },
    methods: {
      rename(v) {
        this.table.rename(v)
      },
      saveComment(v, ov) {
        this.table.save('comment', v, ov)
      },
      format(n){
        return bbn.fn.money(n, false, '');
      },
    }
  }
})();