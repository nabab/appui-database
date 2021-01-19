// Javascript Document
(()=>{
  return{
    computed: {
      data(){
        let d = [];
        bbn.fn.iterate(this.source.data, (v, k) => {
          d.push({
            title: k,
            value: v
          });
        });
        return d;
      }
    }
  }
})();