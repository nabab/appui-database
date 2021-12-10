// Javascript Document

(() => {
  return {
    computed: {
      isConstraint(){
        return false;
      }
    },
    methods: {
      copy() {
        bbn.fn.copy(this.source.id);
      },
      goto(){
        bbn.fn.log(this.source);
      }
    }
  }
})();