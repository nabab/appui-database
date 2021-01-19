(() => {
  return {
    props: {
      source: {
        type: Object,
        validator: v => ('origin' in v) && ('currents' in v)
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