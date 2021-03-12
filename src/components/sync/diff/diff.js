(() => {
  return {
    props: {
      source: {
        type: Object,
        validator: v => ('origin' in v) && ('currents' in v)
      }
    },
    computed:{
      tableSource(){
        let src = [];
        if (this.source.origin
          && this.source.origin.db
          && this.source.origin.data
          && bbn.fn.isObject(this.source.origin.data)
        ) {
          bbn.fn.iterate(this.source.origin.data, (v, f) => {
            let row = {
              field: f,
              [this.source.origin.db]: v
            };
            bbn.fn.each(this.source.currents, c => {
              if (c.db && c.data && bbn.fn.isObject(c.data)) {
                row[c.db] = c.data[f];
              }
            });
            src.push(row);
          });
          return src;
        }
      }
    },
    methods: {
      isSame(val1, val2){
        return bbn.fn.isSame(val1, val2) || (bbn.fn.isNumber(val1) && bbn.fn.isNumber(val2) && (val1 == val2));
      },
      getSource(d){
        let res = [];
        if (d.data && bbn.fn.numProperties(d.data)) {
          bbn.fn.each(this.source.origin.data, (v, k) => {
            res.push({
              field: k,
              value: v,
              db: d.data[k],
              same: this.isSame(v, d.data[k])
            });
          });
        }
        return res;
      }
    }
  }
})();