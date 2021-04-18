( () => {
  return {
    props: ['source'],
    computed: {
      tableSource() {
        let r = [];
        bbn.fn.iterate(this.source.externals, (a, n) => {
          bbn.fn.iterate(a, (b, m) => {
            bbn.fn.each(b, e => {
              r.push({
                table: m,
                column: e,
                link: n
              });
            });
          });
        });
        return r;
      }
    },
    methods: {
      getStateColor(row) {
        let col = false;
        if (!row.is_real) {
          col = 'red';
        } else if (!row.is_virtual) {
          col = 'purple';
        } else if (row.is_same) {
          col = 'green';
        }
        return col;
      },
      writeKey(row) {
        let col = this.getStateColor(row);
        return '<a' + (col ? ' class="bbn-' + col + '"' : '') + '>' + row.name + '</a>';
      },
      writeColInKey(row) {
        return row.columns.join(", ");
      },
    }
  }
})();