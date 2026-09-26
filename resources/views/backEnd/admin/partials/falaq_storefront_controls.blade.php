<style>
.falaq-control-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;}
.falaq-control-card{display:flex;align-items:center;gap:10px;min-height:76px;padding:13px 12px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;color:#173b29;text-decoration:none;transition:border-color .15s,box-shadow .15s,transform .15s;}
.falaq-control-card:hover{border-color:#179d55;box-shadow:0 5px 16px rgba(16,115,64,.10);color:#107340;text-decoration:none;transform:translateY(-1px);}
.falaq-control-card>span:nth-child(2){min-width:0;flex:1;}
.falaq-control-card strong,.falaq-control-card small{display:block;}
.falaq-control-card strong{font-size:12px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.falaq-control-card small{margin-top:3px;color:#9ca3af;font-size:10px;line-height:1.3;}
.falaq-control-icon{width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 34px;border-radius:9px;}
.falaq-control-icon svg{width:16px;height:16px;}
.falaq-control-arrow{width:14px;height:14px;color:#9ca3af;flex:0 0 14px;}
@media(max-width:1100px){.falaq-control-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:700px){.falaq-control-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.falaq-control-grid{grid-template-columns:1fr;}}
</style>
{{-- Falaq Food storefront controls --}}
<div class="section-label">Falaq Food Storefront</div>
<div class="falaq-control-grid">
    @can('banner-list')
    <a href="{{ route('banners.index') }}" class="falaq-control-card">
        <span class="falaq-control-icon" style="background:#ecfdf5;color:#179d55;"><i data-feather="image"></i></span>
        <span><strong>Homepage banners</strong><small>Hero slides and promotions</small></span>
        <i data-feather="arrow-up-right" class="falaq-control-arrow"></i>
    </a>
    @endcan

    @can('category-list')
    <a href="{{ route('categories.index') }}" class="falaq-control-card">
        <span class="falaq-control-icon" style="background:#eff6ff;color:#2563eb;"><i data-feather="grid"></i></span>
        <span><strong>Shop categories</strong><small>Organize storefront navigation</small></span>
        <i data-feather="arrow-up-right" class="falaq-control-arrow"></i>
    </a>
    @endcan

    @can('feature-list')
    <a href="{{ route('features.index') }}" class="falaq-control-card">
        <span class="falaq-control-icon" style="background:#fff7ed;color:#ea580c;"><i data-feather="zap"></i></span>
        <span><strong>Feature strip</strong><small>Delivery and trust highlights</small></span>
        <i data-feather="arrow-up-right" class="falaq-control-arrow"></i>
    </a>
    @endcan

    @can('page-list')
    <a href="{{ route('pages.index') }}" class="falaq-control-card">
        <span class="falaq-control-icon" style="background:#faf5ff;color:#9333ea;"><i data-feather="file-text"></i></span>
        <span><strong>Content pages</strong><small>About, contact and policies</small></span>
        <i data-feather="arrow-up-right" class="falaq-control-arrow"></i>
    </a>
    @endcan

    @can('settings-list')
    <a href="{{ route('settings.index') }}" class="falaq-control-card">
        <span class="falaq-control-icon" style="background:#fef2f2;color:#dc2626;"><i data-feather="sliders"></i></span>
        <span><strong>Store settings</strong><small>Logo, contact and SEO defaults</small></span>
        <i data-feather="arrow-up-right" class="falaq-control-arrow"></i>
    </a>
    @endcan
</div>
