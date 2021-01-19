// Javascript Document
(() => {
  return {
    props: ['source'],
    data(){
      return {
      	tab: this.closest('bbn-router').views[this.closest('bbn-container').idx],
        idx: this.closest('bbn-container').idx,
      }
    },
    computed: {
      is_data(){
        if ( this.closest('bbn-router').views[this.closest('bbn-container').idx].data !== undefined ){
          return true;
        }
        else{
          return false;
        }
      },
      results(){
        return this.closest('bbn-router').views[this.closest('bbn-container').idx].data.data || undefined;
      },
      request(){
        return this.closest('bbn-router').views[this.closest('bbn-container').idx].data.request || '';
      },
      num(){
        return this.closest('bbn-router').views[this.closest('bbn-container').idx].data.num || 0;
      },
      type(){
        return this.closest('bbn-router').views[this.closest('bbn-container').idx].data.type || ''
      },
      columns(){
        let res = [],
            fields =[];
        if ( this.results && bbn.fn.isArray( this.results ) && ( this.results.length > 1 ) ) {
          fields.push(Object.keys(this.results[0]));
          fields[0].forEach( (v, i) => {
            res.push({
              field: v,
              title: v
            });
          });
          return res;
        }
        return res;

      },

    },
    methods: {
     isArray : bbn.fn.isArray,
    }
  }
})();