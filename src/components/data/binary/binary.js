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
        bbn.fn.log(this.source, this.data);
      },
      goto(){
        bbn.fn.log(this.source);
      }
    }
  }
})();