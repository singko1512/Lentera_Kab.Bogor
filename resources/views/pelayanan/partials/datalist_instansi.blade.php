@php
    $instansiList = \App\Models\Dinas::where('is_kesbangpol', false)->orderBy('name')->pluck('name')->toJson();
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    const instansiData = {!! $instansiList !!};
    const inputs = document.querySelectorAll('input[list="instansi-list"]');
    
    inputs.forEach(input => {
        // Hapus atribut list bawaan HTML untuk mematikan UI datalist native
        input.removeAttribute('list');
        input.classList.add('w-full');
        
        // Bungkus input dengan wrapper div ber-position relative
        const wrapper = document.createElement('div');
        wrapper.className = 'relative w-full';
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        
        // Buat container dropdown
        const dropdown = document.createElement('ul');
        // Styling dropdown: di bawah input, scrollable, max-height
        dropdown.className = 'absolute top-full left-0 z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-56 overflow-y-auto hidden mt-1 flex-col py-1';
        wrapper.appendChild(dropdown);
        
        let activeIndex = -1;
        let currentMatches = [];
        
        function renderDropdown(matches) {
            dropdown.innerHTML = '';
            currentMatches = matches;
            activeIndex = -1;
            
            if (matches.length === 0) {
                dropdown.classList.add('hidden');
                return;
            }
            
            matches.forEach((match, index) => {
                const li = document.createElement('li');
                li.className = 'px-4 py-2.5 cursor-pointer hover:bg-blue-50 text-gray-700 text-sm transition-colors border-b border-gray-50 last:border-0';
                li.textContent = match;
                
                li.addEventListener('mousedown', function(e) {
                    e.preventDefault(); // Mencegah input blur agar tidak tertutup sebelum klik selesai
                    input.value = match;
                    dropdown.classList.add('hidden');
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
                
                dropdown.appendChild(li);
            });
            
            dropdown.classList.remove('hidden');
        }
        
        function updateActiveItem() {
            const items = dropdown.querySelectorAll('li');
            items.forEach((item, index) => {
                if (index === activeIndex) {
                    item.classList.add('bg-blue-100', 'text-blue-700', 'font-medium');
                    item.classList.remove('hover:bg-blue-50', 'text-gray-700');
                } else {
                    item.classList.remove('bg-blue-100', 'text-blue-700', 'font-medium');
                    item.classList.add('hover:bg-blue-50', 'text-gray-700');
                }
            });
            if (activeIndex >= 0 && items[activeIndex]) {
                items[activeIndex].scrollIntoView({ block: 'nearest' });
            }
        }
        
        input.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            if (!val) {
                renderDropdown(instansiData);
                return;
            }
            
            const matches = instansiData.filter(item => item.toLowerCase().includes(val));
            renderDropdown(matches);
        });
        
        input.addEventListener('focus', function() {
            const val = this.value.toLowerCase();
            const matches = val ? instansiData.filter(item => item.toLowerCase().includes(val)) : instansiData;
            renderDropdown(matches);
        });
        
        input.addEventListener('blur', function() {
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 150);
        });
        
        input.addEventListener('keydown', function(e) {
            if (dropdown.classList.contains('hidden')) return;
            
            const items = dropdown.querySelectorAll('li');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = (activeIndex + 1) % items.length;
                updateActiveItem();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = (activeIndex - 1 + items.length) % items.length;
                updateActiveItem();
            } else if (e.key === 'Enter') {
                if (activeIndex >= 0 && items[activeIndex]) {
                    e.preventDefault(); // Mencegah submit form
                    input.value = currentMatches[activeIndex];
                    dropdown.classList.add('hidden');
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            } else if (e.key === 'Escape') {
                dropdown.classList.add('hidden');
            }
        });
    });
});
</script>
