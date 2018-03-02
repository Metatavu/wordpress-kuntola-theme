var G = {"ansaitakseni-elantoni":"-2","koska-se-auttaaminua-rentoutumaan":"0","koska-se-on-mielenkiintoista":"1","jotta-voisin-liikkua-yhdessa-muiden-kanssa":"2","kehittyakseni":"2","ollakseni-paremmassa-kunnossa-kuin-muut":"0","koska-minulle-maksetaan-siita":"-1","koska-nautin-ajanvietosta-muiden-seurassa-liikkuen":"0","koska-haluan-hallita-stressia-paremmin":"-1","koska-sen-avulla-keho-pysyy-terveena":"0","koska-se-saa-lihakset-nayttamaan-paremmilta":"-1","ollakseni-fyysisesti-hyvassa-kunnossa":"-2","koska-se-tekee-minut-onnelliseksi":"1","unohtaakseni-tyo-arkihuolet":"0","yllapitaakseni-fyysista-terveyttani":"0","parantaakseni-nykyisia-taitojani":"-1","ollakseni-ryhman-paras":"0","hoitaakseni-sairautta-tai-vaivaa":"0","parantaakseni-suorituskykyani-verrattuna-aikaisempiin-suorituksiini":"2","koska-se-on-yhteista-minulle-ja-ystavilleni":"0","koska-muiden-mielesta-tarvitsen-liikuntaa":"0","koska-se-toimii-stressin-laukaisijana":"0","parantaakseni-kehoni-muotoa":"0","oppiakseni-uusia-taitoja-tai-kokeillakseni-uusia-liikuntamuotoja":"2","koska-se-on-hauskaa":"2","laakarin-fysioterapeutin-tms-maarayksesta":"1","tehdakseni-kuntoni-eteen-enemman-kuin-muut-ihmiset":"0","koska-se-pitaa-minut-terveena":"0","kilpaillakseni-muiden-kanssa":"1","koska-voin-samalla-keskustella-ystavieni-kanssa":"-1","pitaakseni-ylla-nykyista-taitotasoani":"2","parantaakseni-ulkonakoani":"-2","parantaakseni-kestavyyskuntoani":"-2","koska-nautin-liikkumisesta":"0","koska-liikunta-saa-ajatukset-muualle":"0","pudottaakseni-painoa-jotta-nayttaisin-paremmalta":"2","koska-koen-sen-hauskana":"-2","ollakseni-ystavieni-kanssa":"-1","koska-se-auttaa-minua-yllapitamaan-kehoni-jantevana":"-1","page-count":"40","page":"39"};

var out = [];

for (var u = 2; u < 100; u++) {
  var outjs = {};
  for (var k in G) {
    outjs[k] = Math.round((Math.random() * 4) - 2);
  }
  
  var v = JSON.stringify(outjs);
  out.push(`INSERT INTO wp_usermeta (user_id, meta_key, meta_value) values (${u}, 'metaform-85-values', '${v}')`);
}

$('#G').text(out.join(';\n'));