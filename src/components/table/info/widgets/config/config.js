// Javascript Document
(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-database'],
      }
    },
    methods: {
      saveComment(v, ov) {
        this.table.save('comment', v, ov)
      },
      format(n){
        return bbn.fn.money(n, false, '');
      },
    }
  }
})();