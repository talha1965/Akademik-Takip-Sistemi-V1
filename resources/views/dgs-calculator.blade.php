<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-black">
                <h2 class="text-2xl font-bold">DGS Puan Hesaplama Modülü</h2>
                <p class="text-sm opacity-90 mt-1">Netlerinizi girerek tahmini DGS puanınızı anlık olarak hesaplayabilirsiniz.</p>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Sınav Netleri (Max 50 Soru)</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Sayısal Doğru</label>
                            <input type="number" id="say_dogru" min="0" max="50" value="0" oninput="if(this.value>50)this.value=50; if(this.value<0)this.value=0;" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Sayısal Yanlış</label>
                            <input type="number" id="say_yanlis" min="0" max="50" value="0" oninput="if(this.value>50)this.value=50; if(this.value<0)this.value=0;" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Sözel Doğru</label>
                            <input type="number" id="soz_dogru" min="0" max="50" value="0" oninput="if(this.value>50)this.value=50; if(this.value<0)this.value=0;" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Sözel Yanlış</label>
                            <input type="number" id="soz_yanlis" min="0" max="50" value="0" oninput="if(this.value>50)this.value=50; if(this.value<0)this.value=0;" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                        </div>
                    </div>

                    <div class="pt-2">
                        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-3">Akademik Başarı</h3>
                        <label class="block text-sm font-medium text-gray-600">Önlisans Not Ortalaması (AGNO)</label>
                        <input type="number" id="gpa" min="0" max="100" step="0.01" value="3.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="Örn: 3.15 veya 78.50">
                        <p class="text-xs text-gray-400 mt-1">4'lük veya 100'lük sistemde girebilirsiniz. Sistem otomatik algılayacaktır.</p>
                        
                        <div class="mt-3 flex items-start">
                            <div class="flex items-center h-5">
                                <input id="onceki_yerlesme" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                            </div>
                            <label for="onceki_yerlesme" class="ml-2 text-sm font-medium text-gray-700">Önceki DGS'de bir programa yerleştirildim <br><span class="text-xs text-gray-500 font-normal">(ÖBP %25 düşürülerek hesaplanır)</span></label>
                        </div>
                    </div>

                    <button onclick="hesaplaDGS()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition duration-200 mt-4 shadow-md">
                        Puanı Hesapla
                    </button>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Hesaplama Sonuçları</h3>
                        
                        <div id="uyari_kutusu" class="hidden bg-amber-50 border-l-4 border-amber-500 p-4 mb-4">
                            <p id="uyari_metni" class="text-sm text-amber-700 font-medium"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mb-6 text-sm text-gray-600">
                            <div>Sayısal Net: <span id="lbl_say_net" class="font-bold text-gray-800">0.00</span></div>
                            <div>Sözel Net: <span id="lbl_soz_net" class="font-bold text-gray-800">0.00</span></div>
                            <div class="col-span-2 mt-1">ÖBP (Önlisans Başarı Puanı): <span id="lbl_obp" class="font-bold text-gray-800">0.00</span></div>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-white p-4 rounded border border-gray-200 shadow-sm flex justify-between items-center">
                                <span class="font-semibold text-gray-700">DGS Sayısal Puanı:</span>
                                <span id="puan_say" class="text-2xl font-bold text-blue-600">---</span>
                            </div>
                            <div class="bg-white p-4 rounded border border-gray-200 shadow-sm flex justify-between items-center">
                                <span class="font-semibold text-gray-700">DGS Sözel Puanı:</span>
                                <span id="puan_soz" class="text-2xl font-bold text-emerald-600">---</span>
                            </div>
                            <div class="bg-white p-4 rounded border border-gray-200 shadow-sm flex justify-between items-center">
                                <span class="font-semibold text-gray-700">DGS Eşit Ağırlık Puanı:</span>
                                <span id="puan_ea" class="text-2xl font-bold text-purple-600">---</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 mt-6 text-center italic">Hesaplamalarda ÖSYM'nin standart taban katsayıları baz alınmıştır. Gerçek sınavda standart sapmalara göre küçük değişiklikler gösterebilir.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function hesaplaDGS() {
        // Uyarı kutusunu başlangıçta gizle
        const uyariKutusu = document.getElementById('uyari_kutusu');
        const uyariMetni = document.getElementById('uyari_metni');
        uyariKutusu.classList.add('hidden');

        // Input Değerlerini Al
        const sayDogru = parseFloat(document.getElementById('say_dogru').value) || 0;
        const sayYanlis = parseFloat(document.getElementById('say_yanlis').value) || 0;
        const sozDogru = parseFloat(document.getElementById('soz_dogru').value) || 0;
        const sozYanlis = parseFloat(document.getElementById('soz_yanlis').value) || 0;
        let gpa = parseFloat(document.getElementById('gpa').value) || 0;
        const oncekiYerlesme = document.getElementById('onceki_yerlesme').checked;

        // MANTIK KONTROLLERİ (Doğrulamalar)
        if ((sayDogru + sayYanlis) > 50) {
            uyariMetni.innerText = "Hata: Sayısal testinde doğru ve yanlışların toplamı 50'yi geçemez!";
            uyariKutusu.classList.remove('hidden');
            return;
        }
        if ((sozDogru + sozYanlis) > 50) {
            uyariMetni.innerText = "Hata: Sözel testinde doğru ve yanlışların toplamı 50'yi geçemez!";
            uyariKutusu.classList.remove('hidden');
            return;
        }
        if (gpa > 100 || (gpa > 4 && gpa < 40) || gpa < 0) {
            uyariMetni.innerText = "Hata: Lütfen geçerli bir not ortalaması giriniz (0-4 veya 40-100 arası).";
            uyariKutusu.classList.remove('hidden');
            return;
        }

        // Net Hesaplama (4 yanlış 1 doğruyu götürür)
        const sayNet = Math.max(0, sayDogru - (sayYanlis * 0.25));
        const sozNet = Math.max(0, sozDogru - (sozYanlis * 0.25));

        // Arayüzde Netleri Güncelle
        document.getElementById('lbl_say_net').innerText = sayNet.toFixed(2);
        document.getElementById('lbl_soz_net').innerText = sozNet.toFixed(2);

        // 1 Net Kuralı Kontrolü
        if (sayNet < 1 || sozNet < 1) {
            uyariMetni.innerText = "ÖSYM Kuralı: DGS puanınızın hesaplanabilmesi için hem Sayısal hem de Sözel testlerinden en az 1 netiniz olmalıdır.";
            uyariKutusu.classList.remove('hidden');
            document.getElementById('puan_say').innerText = "Hesaplanamadı";
            document.getElementById('puan_soz').innerText = "Hesaplanamadı";
            document.getElementById('puan_ea').innerText = "Hesaplanamadı";
            document.getElementById('lbl_obp').innerText = "0.00";
            return;
        }

        // Gpa Dönüşümü ve ÖBP Hesaplama
        let not100 = gpa;
        if (gpa <= 4.0) {
            not100 = gpa * 25; 
        }
        const obp = not100 * 0.8;
        document.getElementById('lbl_obp').innerText = obp.toFixed(2);

        // Önceki yıl yerleşme kuralı: Katsayı 0.6 yerine 0.45 olur
        const obpKatsayisi = oncekiYerlesme ? 0.45 : 0.6;

        // Güncel Standart DGS Puan Formülleri
        const puanSay = 145 + (sayNet * 3.0) + (sozNet * 0.55) + (obp * obpKatsayisi);
        const puanSoz = 130 + (sayNet * 0.55) + (sozNet * 3.0) + (obp * obpKatsayisi);
        const puanEa  = 135 + (sayNet * 1.7) + (sozNet * 1.7) + (obp * obpKatsayisi);

        // Sonuçları Ekrana Yazdır
        document.getElementById('puan_say').innerText = puanSay.toFixed(3);
        document.getElementById('puan_soz').innerText = puanSoz.toFixed(3);
        document.getElementById('puan_ea').innerText = puanEa.toFixed(3);
    }
    </script>
</x-app-layout>